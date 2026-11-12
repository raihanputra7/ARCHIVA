<?php

namespace App\Services;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\AdminAuditLog;
use App\Models\RiwayatStatus;
use App\Models\User;
use App\Services\Notifications\NotificationDispatcher;
use App\Support\PhoneNormalizer;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminMonitoringService
{
    public function __construct(
        private readonly ?NotificationDispatcher $dispatcher = null
    ) {}

    /**
     * Mengambil data pemantauan akun pengguna ARCHIVA beserta ringkasan per peran dan aktivitas sesi.
     *
     * @return array{
     *     accounts: Collection<int, array<string, mixed>>,
     *     roleCounts: array<string, int>,
     *     activeSessionsCount: int,
     *     verifiedCount: int,
     *     totalCount: int
     * }
     */
    public function getAccountMonitoringSummary(?string $search = null, ?string $roleFilter = null): array
    {
        $query = User::query()
            ->orderByRaw("CASE role
                WHEN 'admin' THEN 1
                WHEN 'kepala_sekolah' THEN 2
                WHEN 'tata_usaha' THEN 3
                WHEN 'guru' THEN 4
                WHEN 'tenaga_pendidik' THEN 5
                ELSE 6 END")
            ->orderBy('name');

        if ($search !== null && trim($search) !== '') {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('unit', 'like', '%'.$term.'%');
            });
        }

        if ($roleFilter !== null && trim($roleFilter) !== '') {
            $query->where('role', trim($roleFilter));
        }

        $users = $query->get();

        $sessionStats = $this->getLatestSessionStats();
        $disposisiCounts = $this->getDisposisiCountsByUser();
        $onlineThreshold = Carbon::now()->subMinutes(15)->getTimestamp();

        $accounts = $users->map(function (User $user) use ($sessionStats, $disposisiCounts, $onlineThreshold): array {
            $roleEnum = $user->role instanceof Role ? $user->role : Role::tryFrom((string) $user->role);
            $session = $sessionStats->get($user->id);
            $lastActivityTs = $session ? (int) $session->last_activity : null;
            $lastActiveAt = $lastActivityTs ? Carbon::createFromTimestamp($lastActivityTs) : null;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_value' => $roleEnum?->value ?? (string) $user->role,
                'role_label' => $roleEnum?->label() ?? ucfirst(str_replace('_', ' ', (string) $user->role)),
                'unit' => $user->unit,
                'email_verified' => $user->email_verified_at !== null,
                'last_active_at' => $lastActiveAt,
                'last_active_human' => $lastActiveAt ? $lastActiveAt->diffForHumans() : 'Belum ada sesi aktif',
                'is_online' => $lastActivityTs !== null && $lastActivityTs >= $onlineThreshold,
                'ip_address' => $session?->ip_address,
                'disposisi_count' => (int) ($disposisiCounts[$user->id] ?? 0),
                'created_at' => $user->created_at,
            ];
        });

        $rawRoleCounts = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        $roleCounts = [];
        foreach (Role::cases() as $roleCase) {
            $roleCounts[$roleCase->value] = (int) ($rawRoleCounts[$roleCase->value] ?? 0);
        }

        return [
            'accounts' => $accounts,
            'roleCounts' => $roleCounts,
            'activeSessionsCount' => $sessionStats->filter(fn ($s) => (int) $s->last_activity >= $onlineThreshold)->count(),
            'verifiedCount' => User::whereNotNull('email_verified_at')->count(),
            'totalCount' => User::count(),
        ];
    }

    /**
     * Mengambil log audit riwayat status persuratan sistem dengan paginasi.
     */
    public function getSystemAuditLogs(int $perPage = 15): LengthAwarePaginator
    {
        return RiwayatStatus::with(['suratMasuk', 'user'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Daftar akun Admin aktif untuk informasi kontak bantuan teknis.
     */
    public function getAdminContacts(): Collection
    {
        return User::query()
            ->where('role', Role::ADMIN->value)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'unit']);
    }

    /**
     * Mendaftarkan akun pengguna ARCHIVA baru oleh Admin.
     *
     * @param  array{name: string, email: string, role: string, unit?: ?string, password: string}  $data
     */
    public function createUser(array $data, User $actor): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'unit' => $data['unit'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => AccountStatus::ACTIVE->value,
            'notify_email' => true,
            'approved_by' => $actor->id,
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        Log::info('Admin membuat akun pengguna ARCHIVA baru', [
            'actor_id' => $actor->id,
            'created_user_id' => $user->id,
            'role' => $data['role'],
        ]);

        return $user;
    }

    /**
     * Mengatur ulang kata sandi pengguna oleh Admin ketika ada kendala akses akun.
     */
    public function resetPassword(User $targetUser, string $newPassword, User $actor): void
    {
        $targetUser->update([
            'password' => Hash::make($newPassword),
        ]);

        Log::info('Admin melakukan reset kata sandi pengguna', [
            'actor_id' => $actor->id,
            'target_user_id' => $targetUser->id,
        ]);
    }

    private function getLatestSessionStats(): Collection
    {
        if (! Schema::hasTable('sessions')) {
            return collect();
        }

        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'), DB::raw('MAX(ip_address) as ip_address'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
    }

    private function getDisposisiCountsByUser(): Collection
    {
        if (! Schema::hasTable('disposisi')) {
            return collect();
        }

        return DB::table('disposisi')
            ->select('to_user_id as uid', DB::raw('COUNT(*) as total'))
            ->whereNotNull('to_user_id')
            ->groupBy('to_user_id')
            ->pluck('total', 'uid');
    }

    /**
     * Mengambil daftar permintaan pendaftaran & status akun untuk Admin.
     */
    public function getRegistrationRequests(?string $statusFilter = 'pending', ?string $search = null): LengthAwarePaginator
    {
        $query = User::query()->latest('created_at');

        if ($statusFilter !== null && $statusFilter !== '' && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== null && trim($search) !== '') {
            $term = '%'.trim($search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone_wa', 'like', $term);
            });
        }

        return $query->paginate(15)->withQueryString();
    }

    public function getPendingRegistrationsCount(): int
    {
        return User::where('status', AccountStatus::PENDING->value)->count();
    }

    public function approveRegistration(User $targetUser, string $finalRole, User $actor, ?string $ipAddress = null): User
    {
        if ($targetUser->email_verified_at === null) {
            throw new \DomainException('Email pendaftar belum terverifikasi. Pendaftar wajib memverifikasi email sebelum dapat disetujui.');
        }

        $roleEnum = Role::from($finalRole);
        $previousRole = $targetUser->role instanceof Role ? $targetUser->role->value : (string) $targetUser->role;

        $targetUser->update([
            'role' => $roleEnum->value,
            'status' => AccountStatus::ACTIVE->value,
            'rejected_reason' => null,
            'approved_by' => $actor->id,
            'approved_at' => now(),
        ]);

        $this->recordAdminAudit($actor, $targetUser, 'persetujuan_akun', [
            'requested_role' => $targetUser->requested_role,
            'previous_role' => $previousRole,
            'final_role' => $roleEnum->value,
        ], $ipAddress);

        if ($previousRole !== $roleEnum->value) {
            $this->recordAdminAudit($actor, $targetUser, 'perubahan_peran', [
                'from_role' => $previousRole,
                'to_role' => $roleEnum->value,
            ], $ipAddress);
        }

        ($this->dispatcher ?? app(NotificationDispatcher::class))->notifyRegistrationDecision($targetUser, true);

        return $targetUser;
    }

    public function rejectRegistration(User $targetUser, ?string $reason, User $actor, ?string $ipAddress = null): User
    {
        $cleanReason = $reason !== null && trim($reason) !== '' ? trim($reason) : null;

        $targetUser->update([
            'status' => AccountStatus::REJECTED->value,
            'rejected_reason' => $cleanReason,
            'approved_by' => $actor->id,
            'approved_at' => null,
        ]);

        $this->recordAdminAudit($actor, $targetUser, 'penolakan_akun', [
            'reason' => $cleanReason,
        ], $ipAddress);

        ($this->dispatcher ?? app(NotificationDispatcher::class))->notifyRegistrationDecision($targetUser, false, $cleanReason);

        return $targetUser;
    }

    public function toggleUserStatus(User $targetUser, User $actor, ?string $ipAddress = null): User
    {
        if ($targetUser->id === $actor->id) {
            throw new \DomainException('Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $current = $targetUser->status ?? AccountStatus::ACTIVE->value;
        $nextStatus = $current === AccountStatus::ACTIVE->value
            ? AccountStatus::INACTIVE->value
            : AccountStatus::ACTIVE->value;

        $targetUser->update(['status' => $nextStatus]);

        $action = $nextStatus === AccountStatus::INACTIVE->value ? 'nonaktifkan_akun' : 'aktifkan_akun';
        $this->recordAdminAudit($actor, $targetUser, $action, [
            'from_status' => $current,
            'to_status' => $nextStatus,
        ], $ipAddress);

        return $targetUser;
    }

    /**
     * @param  array{email: string, phone_wa?: ?string, role?: ?string}  $data
     */
    public function updateUserContactAndRole(User $targetUser, array $data, User $actor, ?string $ipAddress = null): User
    {
        $previousRole = $targetUser->role instanceof Role ? $targetUser->role->value : (string) $targetUser->role;
        $newRole = ! empty($data['role']) ? Role::from($data['role'])->value : $previousRole;
        $normalizedPhone = ! empty($data['phone_wa']) ? PhoneNormalizer::normalize($data['phone_wa']) : null;

        $targetUser->update([
            'email' => Str::lower(trim($data['email'])),
            'phone_wa' => $normalizedPhone,
            'role' => $newRole,
        ]);

        if ($previousRole !== $newRole) {
            $this->recordAdminAudit($actor, $targetUser, 'perubahan_peran', [
                'from_role' => $previousRole,
                'to_role' => $newRole,
            ], $ipAddress);
        } else {
            $this->recordAdminAudit($actor, $targetUser, 'pembaruan_kontak', [
                'email' => $targetUser->email,
            ], $ipAddress);
        }

        return $targetUser;
    }

    public function getAdminAuditLogs(int $perPage = 15): LengthAwarePaginator
    {
        return AdminAuditLog::with(['actor', 'targetUser'])
            ->latest('created_at')
            ->paginate($perPage, ['*'], 'admin_page');
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function recordAdminAudit(User $actor, User $targetUser, string $action, array $details, ?string $ipAddress): void
    {
        if (! Schema::hasTable('admin_audit_logs')) {
            return;
        }

        AdminAuditLog::create([
            'actor_id' => $actor->id,
            'target_user_id' => $targetUser->id,
            'action' => $action,
            'details' => $details,
            'ip_address' => $ipAddress,
            'created_at' => now(),
        ]);
    }
}

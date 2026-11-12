<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Http\Requests\Admin\MonitorUsersRequest;
use App\Http\Requests\Admin\ResetUserPasswordRequest;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Models\User;
use App\Services\AdminMonitoringService;
use App\Support\PhoneNormalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminMonitoringService $adminService
    ) {}

    /**
     * Halaman Pemantauan & Tata Kelola Akun Pengguna ARCHIVA.
     */
    public function users(MonitorUsersRequest $request): View
    {
        $validated = $request->validated();
        $search = $validated['q'] ?? null;
        $roleFilter = $validated['role'] ?? null;

        $monitoring = $this->adminService->getAccountMonitoringSummary($search, $roleFilter);
        $roles = Role::cases();

        return view('dashboard.admin.users', compact('monitoring', 'roles', 'search', 'roleFilter'));
    }

    /**
     * Simpan akun pengguna baru dari panel Admin.
     */
    public function storeUser(StoreAdminUserRequest $request): RedirectResponse
    {
        $user = $this->adminService->createUser($request->validated(), $request->user());

        return redirect()
            ->route('dashboard.admin.users')
            ->with('status', 'Akun '.$user->name.' berhasil didaftarkan.');
    }

    /**
     * Reset kata sandi pengguna yang mengalami kendala login.
     */
    public function resetPassword(ResetUserPasswordRequest $request, User $user): RedirectResponse
    {
        $this->adminService->resetPassword($user, $request->validated('new_password'), $request->user());

        return redirect()
            ->route('dashboard.admin.users')
            ->with('status', 'Kata sandi untuk '.$user->name.' berhasil diatur ulang.');
    }

    /**
     * Halaman Log Audit Sistem (Riwayat Status & Aktivitas Persuratan + Tata Kelola Akun).
     */
    public function audit(): View
    {
        $auditLogs = $this->adminService->getSystemAuditLogs(15);
        $adminAuditLogs = $this->adminService->getAdminAuditLogs(15);

        return view('dashboard.admin.audit', compact('auditLogs', 'adminAuditLogs'));
    }

    /**
     * Halaman Permintaan Pendaftaran & Persetujuan Akun oleh Admin.
     */
    public function registrations(Request $request): View
    {
        $statusFilter = $request->query('status', AccountStatus::PENDING->value);
        $allowedStatuses = array_merge(['all'], array_map(fn (AccountStatus $s) => $s->value, AccountStatus::cases()));
        if (! in_array($statusFilter, $allowedStatuses, true)) {
            $statusFilter = AccountStatus::PENDING->value;
        }

        $search = $request->query('q');
        $registrations = $this->adminService->getRegistrationRequests($statusFilter, $search);
        $pendingCount = $this->adminService->getPendingRegistrationsCount();
        $roles = Role::cases();
        $statuses = AccountStatus::cases();

        return view('dashboard.admin.registrations', compact(
            'registrations',
            'pendingCount',
            'statusFilter',
            'search',
            'roles',
            'statuses',
        ));
    }

    public function approveRegistration(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(array_map(fn (Role $r) => $r->value, Role::cases()))],
        ]);

        try {
            $this->adminService->approveRegistration(
                $user,
                $validated['role'],
                $request->user(),
                $request->ip()
            );

            return back()->with('status', 'Akun '.$user->name.' berhasil disetujui dan diaktifkan.');
        } catch (\DomainException $e) {
            return back()->withErrors(['approval' => $e->getMessage()]);
        }
    }

    public function rejectRegistration(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'rejected_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $this->adminService->rejectRegistration(
            $user,
            $validated['rejected_reason'] ?? null,
            $request->user(),
            $request->ip()
        );

        return back()->with('status', 'Pendaftaran '.$user->name.' telah ditolak.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        try {
            $updated = $this->adminService->toggleUserStatus($user, $request->user(), $request->ip());
            $label = $updated->status === AccountStatus::ACTIVE->value ? 'diaktifkan kembali' : 'dinonaktifkan';

            return back()->with('status', 'Akun '.$updated->name.' berhasil '.$label.'.');
        } catch (\DomainException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', Rule::unique(User::class)->ignore($user->id)],
            'phone_wa' => [
                'nullable',
                'string',
                'max:25',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== null && trim((string) $value) !== '' && ! PhoneNormalizer::isValid((string) $value)) {
                        $fail('Nomor WhatsApp harus valid dalam format 08xx, 62xx, atau +62xx.');
                    }
                },
            ],
            'role' => ['required', 'string', Rule::in(array_map(fn (Role $r) => $r->value, Role::cases()))],
        ]);

        $this->adminService->updateUserContactAndRole($user, $validated, $request->user(), $request->ip());

        return back()->with('status', 'Data akun '.$user->name.' berhasil diperbarui.');
    }
}

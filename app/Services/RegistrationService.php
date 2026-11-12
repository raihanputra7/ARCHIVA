<?php

namespace App\Services;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Services\Notifications\NotificationDispatcher;
use App\Support\PhoneNormalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationService
{
    /**
     * Peran yang diizinkan pada form pendaftaran publik.
     *
     * @return list<string>
     */
    public static function allowedPublicRoles(): array
    {
        return [
            Role::GURU->value,
            Role::TATA_USAHA->value,
            Role::TENAGA_PENDIDIK->value,
        ];
    }

    public function __construct(
        private readonly NotificationDispatcher $dispatcher
    ) {}

    /**
     * Mendaftarkan pengguna baru dengan status pending dan mengirim token verifikasi email.
     *
     * @param  array{name: string, email: string, phone_wa: string, password: string, requested_role: string}  $data
     */
    public function register(array $data): User
    {
        $requestedRole = in_array($data['requested_role'], self::allowedPublicRoles(), true)
            ? $data['requested_role']
            : Role::GURU->value;

        $normalizedPhone = PhoneNormalizer::normalize($data['phone_wa']);

        [$user, $plainToken] = DB::transaction(function () use ($data, $requestedRole, $normalizedPhone): array {
            $user = User::create([
                'name' => trim($data['name']),
                'email' => Str::lower(trim($data['email'])),
                'phone_wa' => $normalizedPhone,
                'password' => Hash::make($data['password']),
                'role' => $requestedRole,
                'requested_role' => $requestedRole,
                'status' => AccountStatus::PENDING->value,
                'email_verified_at' => null,
                'notify_email' => true,
            ]);

            $plainToken = $this->issueTokenForUser($user);

            return [$user, $plainToken];
        });

        $this->dispatcher->sendVerificationEmail($user, $plainToken);

        return $user;
    }

    public function issueTokenForUser(User $user): string
    {
        EmailVerificationToken::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $plainToken = Str::random(64);

        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addHours(24),
            'created_at' => now(),
        ]);

        return $plainToken;
    }

    /**
     * Memverifikasi token email (sekali pakai, kedaluwarsa 24 jam).
     *
     * @return array{success: bool, message: string, user?: User}
     */
    public function verifyToken(string $plainToken): array
    {
        $tokenHash = hash('sha256', $plainToken);
        $record = EmailVerificationToken::with('user')
            ->where('token_hash', $tokenHash)
            ->first();

        if (! $record || ! $record->user) {
            return [
                'success' => false,
                'message' => 'Tautan verifikasi tidak valid atau tidak ditemukan.',
            ];
        }

        if ($record->used_at !== null) {
            return [
                'success' => false,
                'message' => 'Tautan verifikasi ini sudah pernah digunakan.',
            ];
        }

        if ($record->expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'Tautan verifikasi telah kedaluwarsa (lebih dari 24 jam). Silakan minta tautan baru.',
            ];
        }

        $user = $record->user;

        DB::transaction(function () use ($record, $user) {
            $record->update(['used_at' => now()]);

            if ($user->email_verified_at === null) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        });

        if ($user->status === AccountStatus::PENDING->value) {
            $this->dispatcher->notifyAdminNewRegistration($user);
        }

        return [
            'success' => true,
            'message' => 'Alamat email Anda berhasil diverifikasi. Akun Anda kini siap ditinjau oleh Administrator.',
            'user' => $user,
        ];
    }

    /**
     * Mengirim ulang verifikasi email tanpa membocorkan apakah email terdaftar.
     */
    public function resendVerification(string $email): void
    {
        $normalizedEmail = Str::lower(trim($email));
        $user = User::where('email', $normalizedEmail)->first();

        if (! $user || $user->email_verified_at !== null) {
            return;
        }

        if ($user->status === AccountStatus::REJECTED->value) {
            return;
        }

        $plainToken = $this->issueTokenForUser($user);
        $this->dispatcher->sendVerificationEmail($user, $plainToken);
    }
}

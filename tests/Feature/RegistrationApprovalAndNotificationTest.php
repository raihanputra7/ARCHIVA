<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\Role;
use App\Mail\AdminNewRegistrationMail;
use App\Mail\DisposisiCreatedMail;
use App\Mail\RegistrationStatusMail;
use App\Mail\VerifyEmailMail;
use App\Models\AdminAuditLog;
use App\Models\EmailVerificationToken;
use App\Models\NotificationLog;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationApprovalAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_rejects_admin_and_kepala_sekolah_roles(): void
    {
        Mail::fake();

        foreach ([Role::ADMIN->value, Role::KEPALA_SEKOLAH->value] as $forbiddenRole) {
            $response = $this->post('/daftar', [
                'name' => 'Pengguna Uji',
                'email' => "test_{$forbiddenRole}@gmail.com",
                'phone_wa' => '081234567890',
                'requested_role' => $forbiddenRole,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertSessionHasErrors('requested_role');
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_public_registration_normalizes_wa_hashes_token_and_sends_verification_email(): void
    {
        Mail::fake();

        $response = $this->post('/daftar', [
            'name' => 'Budi Guru Baru',
            'email' => 'BudiGuru@gmail.com',
            'phone_wa' => '+62 812-3456-7890',
            'requested_role' => Role::GURU->value,
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('register.accepted'));

        $user = User::where('email', 'budiguru@gmail.com')->firstOrFail();
        $this->assertSame('6281234567890', $user->phone_wa);
        $this->assertSame(AccountStatus::PENDING->value, $user->status);
        $this->assertSame(Role::GURU->value, $user->requested_role);
        $this->assertNull($user->email_verified_at);

        $this->assertDatabaseHas('email_verification_tokens', [
            'user_id' => $user->id,
        ]);

        Mail::assertSent(VerifyEmailMail::class, fn (VerifyEmailMail $mail) => $mail->hasTo('budiguru@gmail.com'));
    }

    public function test_non_active_users_cannot_login(): void
    {
        $pending = User::factory()->pending()->create(['role' => Role::GURU->value]);
        $rejected = User::factory()->create(['role' => Role::GURU->value, 'status' => AccountStatus::REJECTED->value]);
        $inactive = User::factory()->create(['role' => Role::GURU->value, 'status' => AccountStatus::INACTIVE->value]);
        $active = User::factory()->create(['role' => Role::GURU->value, 'status' => AccountStatus::ACTIVE->value]);

        foreach ([$pending, $rejected, $inactive] as $blockedUser) {
            $this->post('/login', [
                'email' => $blockedUser->email,
                'password' => 'password',
            ])->assertSessionHasErrors('email');

            $this->assertGuest();
        }

        $this->post('/login', [
            'email' => $active->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($active);
    }

    public function test_email_verification_token_is_single_use_and_notifies_admin_when_configured(): void
    {
        Mail::fake();
        config(['archiva.admin_notify_email' => 'admin-notif@archiva.test']);

        $service = app(RegistrationService::class);
        $user = $service->register([
            'name' => 'Dewi TU',
            'email' => 'dewitu@gmail.com',
            'phone_wa' => '081298765432',
            'requested_role' => Role::TATA_USAHA->value,
            'password' => 'password123',
        ]);

        // Re-issue a known token so we can test the exact verification route
        $plainToken = $service->issueTokenForUser($user);

        $this->get("/verifikasi-email/{$plainToken}")
            ->assertOk()
            ->assertSee('Email Berhasil Diverifikasi');

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        Mail::assertSent(AdminNewRegistrationMail::class, fn ($mail) => $mail->hasTo('admin-notif@archiva.test'));

        // Token cannot be reused
        $this->get("/verifikasi-email/{$plainToken}")
            ->assertOk()
            ->assertSee('sudah pernah digunakan');
    }

    public function test_expired_verification_token_is_rejected(): void
    {
        Mail::fake();

        $service = app(RegistrationService::class);
        $user = $service->register([
            'name' => 'Rina Tendik',
            'email' => 'rinatendik@gmail.com',
            'phone_wa' => '081211112222',
            'requested_role' => Role::TENAGA_PENDIDIK->value,
            'password' => 'password123',
        ]);

        $plainToken = $service->issueTokenForUser($user);
        EmailVerificationToken::where('user_id', $user->id)
            ->whereNull('used_at')
            ->update(['expires_at' => now()->subMinute()]);

        $this->get("/verifikasi-email/{$plainToken}")
            ->assertOk()
            ->assertSee('kedaluwarsa');

        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_admin_cannot_approve_unverified_user_but_can_approve_verified_and_change_role(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => Role::ADMIN->value]);
        $unverified = User::factory()->pending()->unverified()->create([
            'role' => Role::GURU->value,
            'requested_role' => Role::GURU->value,
        ]);

        $this->actingAs($admin)
            ->patch(route('dashboard.admin.registrations.approve', $unverified), [
                'role' => Role::TATA_USAHA->value,
            ])
            ->assertSessionHasErrors('approval');

        $this->assertSame(AccountStatus::PENDING->value, $unverified->fresh()->status);

        // Now verify email
        $unverified->update(['email_verified_at' => now()]);

        $this->actingAs($admin)
            ->patch(route('dashboard.admin.registrations.approve', $unverified), [
                'role' => Role::TATA_USAHA->value,
            ])
            ->assertSessionHasNoErrors();

        $unverified->refresh();
        $this->assertSame(AccountStatus::ACTIVE->value, $unverified->status);
        $this->assertSame(Role::TATA_USAHA, $unverified->role);
        $this->assertSame($admin->id, $unverified->approved_by);

        $this->assertDatabaseHas('admin_audit_logs', [
            'actor_id' => $admin->id,
            'target_user_id' => $unverified->id,
            'action' => 'persetujuan_akun',
        ]);
        $this->assertDatabaseHas('admin_audit_logs', [
            'actor_id' => $admin->id,
            'target_user_id' => $unverified->id,
            'action' => 'perubahan_peran',
        ]);

        Mail::assertSent(RegistrationStatusMail::class, fn ($mail) => $mail->hasTo($unverified->email) && $mail->approved === true);
    }

    public function test_admin_can_reject_and_toggle_user_status_with_audit_log(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => Role::ADMIN->value]);
        $applicant = User::factory()->pending()->create(['role' => Role::GURU->value]);

        $this->actingAs($admin)
            ->patch(route('dashboard.admin.registrations.reject', $applicant), [
                'rejected_reason' => 'Bukan staf sekolah aktif.',
            ])
            ->assertSessionHasNoErrors();

        $applicant->refresh();
        $this->assertSame(AccountStatus::REJECTED->value, $applicant->status);
        $this->assertSame('Bukan staf sekolah aktif.', $applicant->rejected_reason);

        Mail::assertSent(RegistrationStatusMail::class, fn ($mail) => $mail->hasTo($applicant->email) && $mail->approved === false);

        $activeUser = User::factory()->create(['role' => Role::GURU->value]);
        $this->actingAs($admin)
            ->patch(route('dashboard.admin.users.toggle-status', $activeUser))
            ->assertSessionHasNoErrors();

        $this->assertSame(AccountStatus::INACTIVE->value, $activeUser->fresh()->status);
        $this->assertSame(1, AdminAuditLog::where('action', 'nonaktifkan_akun')->where('target_user_id', $activeUser->id)->count());
    }

    public function test_profile_update_allows_wa_and_notify_email_and_requires_reverification_on_email_change(): void
    {
        Mail::fake();

        $user = User::factory()->create(['role' => Role::GURU->value]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => $user->name,
                'email' => 'newemail@gmail.com',
                'phone_wa' => '081355556666',
                'notify_email' => '0',
            ])
            ->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertSame('newemail@gmail.com', $user->email);
        $this->assertSame('6281355556666', $user->phone_wa);
        $this->assertFalse($user->notify_email);
        $this->assertNull($user->email_verified_at);

        Mail::assertSent(VerifyEmailMail::class, fn ($mail) => $mail->hasTo('newemail@gmail.com'));
    }

    public function test_disposisi_triggers_email_log_and_wa_link_without_blocking_on_mail_failure(): void
    {
        Mail::fake();

        $kepsek = User::factory()->create(['role' => Role::KEPALA_SEKOLAH->value]);
        $tu = User::factory()->create(['role' => Role::TATA_USAHA->value]);
        $guru = User::factory()->create([
            'role' => Role::GURU->value,
            'phone_wa' => '6281234567001',
            'notify_email' => true,
        ]);

        $surat = SuratMasuk::factory()->create([
            'dicatat_oleh' => $tu->id,
            'created_by' => $tu->id,
            'status' => 'Diterima',
        ]);

        $response = $this->actingAs($kepsek)
            ->post(route('disposisi.store', $surat), [
                'to_user_ids' => [$guru->id],
                'instruksi' => 'Mohon segera ditindaklanjuti.',
                'sifat' => 'Segera',
                'batas_waktu' => now()->addDays(3)->format('Y-m-d'),
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('disposisi_created_ids');

        Mail::assertSent(DisposisiCreatedMail::class, fn ($mail) => $mail->hasTo($guru->email));

        $this->assertDatabaseHas('notification_logs', [
            'user_id' => $guru->id,
            'surat_id' => $surat->id,
            'channel' => 'email',
            'status' => 'sent',
        ]);

        // Authorized creator sees WhatsApp link
        $this->actingAs($kepsek)
            ->get(route('surat-masuk.show', $surat))
            ->assertOk()
            ->assertSee('https://wa.me/6281234567001', false);

        // Recipient Guru does NOT see WhatsApp link for creator-only action
        $this->actingAs($guru)
            ->get(route('surat-masuk.show', $surat))
            ->assertOk()
            ->assertDontSee('https://wa.me/6281234567001', false);

        $this->assertGreaterThan(0, NotificationLog::count());
    }

    public function test_email_failure_does_not_fail_disposisi_and_logs_failed_status(): void
    {
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('SMTP connection refused'));

        $kepsek = User::factory()->create(['role' => Role::KEPALA_SEKOLAH->value]);
        $tu = User::factory()->create(['role' => Role::TATA_USAHA->value]);
        $guru = User::factory()->create([
            'role' => Role::GURU->value,
            'notify_email' => true,
        ]);

        $surat = SuratMasuk::factory()->create([
            'dicatat_oleh' => $tu->id,
            'created_by' => $tu->id,
            'status' => 'Diterima',
        ]);

        $response = $this->actingAs($kepsek)
            ->post(route('disposisi.store', $surat), [
                'to_user_ids' => [$guru->id],
                'instruksi' => 'Tetap diproses meski SMTP mati.',
                'sifat' => 'Biasa',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('disposisi_created_ids');

        $this->assertDatabaseHas('disposisi', [
            'surat_masuk_id' => $surat->id,
            'to_user_id' => $guru->id,
        ]);

        $this->assertDatabaseHas('notification_logs', [
            'user_id' => $guru->id,
            'surat_id' => $surat->id,
            'channel' => 'email',
            'status' => 'failed',
        ]);
    }
}

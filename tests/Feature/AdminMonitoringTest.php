<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\RiwayatStatus;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_account_monitoring_panel_and_role_appropriate_sidebar(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama@archiva.test',
            'role' => Role::ADMIN,
        ]);

        $guru = User::factory()->create([
            'name' => 'Ibu Ratna',
            'email' => 'ratna@archiva.test',
            'role' => Role::GURU,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.admin'));

        $response->assertOk();
        $response->assertSee('Panel Pemantauan Akun ARCHIVA');
        $response->assertSee($admin->email);
        $response->assertSee($guru->name);
        $response->assertSee($guru->email);

        // Sidebar admin menggunakan menu sesuai peran (tidak ada Surat Masuk / Surat Keluar)
        $response->assertSee('Pantau Akun');
        $response->assertSee('Log Audit Sistem');
        $response->assertDontSee('href="'.route('surat-masuk.index').'"', false);
        $response->assertDontSee('href="'.route('surat-keluar.index').'"', false);
    }

    public function test_admin_can_filter_and_search_accounts_on_users_page(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $tu = User::factory()->create([
            'name' => 'Budi TU',
            'email' => 'budi.tu@archiva.test',
            'role' => Role::TATA_USAHA,
            'unit' => 'Dapodik',
        ]);
        $guru = User::factory()->create([
            'name' => 'Siti Guru',
            'email' => 'siti.guru@archiva.test',
            'role' => Role::GURU,
        ]);

        // Filter berdasarkan role tata_usaha
        $resRole = $this->actingAs($admin)->get(route('dashboard.admin.users', ['role' => 'tata_usaha']));
        $resRole->assertOk();
        $resRole->assertSee($tu->email);
        $resRole->assertDontSee($guru->email);

        // Cari berdasarkan keyword nama/email
        $resSearch = $this->actingAs($admin)->get(route('dashboard.admin.users', ['q' => 'siti.guru']));
        $resSearch->assertOk();
        $resSearch->assertSee($guru->name);
        $resSearch->assertDontSee($tu->email);
    }

    public function test_admin_can_create_user_and_reset_password(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        $storeResponse = $this->actingAs($admin)->post(route('dashboard.admin.users.store'), [
            'name' => 'Guru Baru',
            'email' => 'guru.baru@archiva.test',
            'role' => Role::GURU->value,
            'unit' => 'Kurikulum',
            'password' => 'password123',
        ]);

        $storeResponse->assertRedirect(route('dashboard.admin.users'));
        $this->assertDatabaseHas('users', [
            'email' => 'guru.baru@archiva.test',
            'role' => Role::GURU->value,
            'unit' => 'Kurikulum',
        ]);

        $targetUser = User::where('email', 'guru.baru@archiva.test')->firstOrFail();

        $resetResponse = $this->actingAs($admin)->patch(
            route('dashboard.admin.users.reset-password', $targetUser),
            ['new_password' => 'sandibaru99']
        );

        $resetResponse->assertRedirect(route('dashboard.admin.users'));
        $this->assertTrue(Hash::check('sandibaru99', $targetUser->fresh()->password));
    }

    public function test_admin_can_view_system_audit_logs(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $tu = User::factory()->create(['name' => 'Petugas TU', 'role' => Role::TATA_USAHA]);

        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/2026/9001',
            'pengirim' => 'Sudin Pendidikan',
            'perihal' => 'Uji Audit Sistem',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'diterima',
            'created_by' => $tu->id,
        ]);

        RiwayatStatus::create([
            'surat_masuk_id' => $surat->id,
            'dari_status' => null,
            'ke_status' => 'diterima',
            'user_id' => $tu->id,
            'catatan' => 'Registrasi surat uji audit',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.admin.audit'));
        $response->assertOk();
        $response->assertSee('ARC/SM/2026/9001');
        $response->assertSee('Registrasi surat uji audit');
    }

    public function test_non_admin_cannot_access_admin_monitoring_endpoints(): void
    {
        $guru = User::factory()->create(['role' => Role::GURU]);
        $target = User::factory()->create(['role' => Role::TENAGA_PENDIDIK]);

        $this->actingAs($guru)->get(route('dashboard.admin.users'))->assertForbidden();
        $this->actingAs($guru)->get(route('dashboard.admin.audit'))->assertForbidden();
        $this->actingAs($guru)->post(route('dashboard.admin.users.store'), [
            'name' => 'Ilegal',
            'email' => 'ilegal@archiva.test',
            'role' => Role::GURU->value,
            'password' => 'password123',
        ])->assertForbidden();
        $this->actingAs($guru)->patch(route('dashboard.admin.users.reset-password', $target), [
            'new_password' => 'password123',
        ])->assertForbidden();
    }

    public function test_profile_page_displays_admin_contact_for_support(): void
    {
        $admin = User::factory()->create([
            'name' => 'Ahmad Admin',
            'email' => 'bantuan.admin@archiva.test',
            'role' => Role::ADMIN,
        ]);
        $guru = User::factory()->create(['role' => Role::GURU]);

        $response = $this->actingAs($guru)->get(route('profile.edit'));
        $response->assertOk();
        $response->assertSee('Ahmad Admin');
        $response->assertSee('bantuan.admin@archiva.test');
    }
}

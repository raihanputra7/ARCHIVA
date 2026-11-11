<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_is_disabled(): void
    {
        $response = $this->get('/register');

        $response->assertNotFound();
    }

    public function test_unauthenticated_user_cannot_access_dashboards(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/dashboard/admin')->assertRedirect('/login');
        $this->get('/dashboard/kepala-sekolah')->assertRedirect('/login');
        $this->get('/dashboard/tata-usaha')->assertRedirect('/login');
        $this->get('/dashboard/guru')->assertRedirect('/login');
        $this->get('/dashboard/tenaga-pendidik')->assertRedirect('/login');
    }

    public function test_dashboard_redirects_to_correct_dashboard_per_role(): void
    {
        $roles = [
            Role::ADMIN->value => route('dashboard.admin'),
            Role::KEPALA_SEKOLAH->value => route('dashboard.kepala-sekolah'),
            Role::TATA_USAHA->value => route('dashboard.tata-usaha'),
            Role::GURU->value => route('dashboard.guru'),
            Role::TENAGA_PENDIDIK->value => route('dashboard.tenaga-pendidik'),
        ];

        foreach ($roles as $roleValue => $expectedRoute) {
            $user = User::factory()->create([
                'role' => $roleValue,
            ]);

            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertRedirect($expectedRoute);
        }
    }

    public function test_users_can_access_their_own_role_dashboard(): void
    {
        $roleRoutes = [
            Role::ADMIN->value => '/dashboard/admin',
            Role::KEPALA_SEKOLAH->value => '/dashboard/kepala-sekolah',
            Role::TATA_USAHA->value => '/dashboard/tata-usaha',
            Role::GURU->value => '/dashboard/guru',
            Role::TENAGA_PENDIDIK->value => '/dashboard/tenaga-pendidik',
        ];

        foreach ($roleRoutes as $roleValue => $url) {
            $user = User::factory()->create([
                'role' => $roleValue,
            ]);

            $response = $this->actingAs($user)->get($url);
            $response->assertOk();
        }
    }

    public function test_users_cannot_access_other_roles_dashboard_and_receive_403(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $kepsek = User::factory()->create(['role' => Role::KEPALA_SEKOLAH]);
        $tu = User::factory()->create(['role' => Role::TATA_USAHA]);
        $guru = User::factory()->create(['role' => Role::GURU]);
        $tendik = User::factory()->create(['role' => Role::TENAGA_PENDIDIK]);

        // Guru trying to access admin dashboard -> 403
        $this->actingAs($guru)->get('/dashboard/admin')->assertForbidden();

        // Guru trying to access kepala-sekolah dashboard -> 403
        $this->actingAs($guru)->get('/dashboard/kepala-sekolah')->assertForbidden();

        // TU trying to access admin dashboard -> 403
        $this->actingAs($tu)->get('/dashboard/admin')->assertForbidden();

        // Kepsek trying to access tata-usaha dashboard -> 403
        $this->actingAs($kepsek)->get('/dashboard/tata-usaha')->assertForbidden();

        // Tendik trying to access admin dashboard -> 403
        $this->actingAs($tendik)->get('/dashboard/admin')->assertForbidden();
    }

    public function test_sidebar_active_state_switches_correctly_per_route(): void
    {
        $tu = User::factory()->create(['role' => Role::TATA_USAHA]);

        // 1. Kunjungi Dashboard Tata Usaha
        $responseDashboard = $this->actingAs($tu)->get(route('dashboard.tata-usaha'));
        $responseDashboard->assertOk();
        $contentDash = $responseDashboard->getContent();

        // Dashboard aktif, Surat Masuk & Surat Keluar tidak aktif
        $this->assertStringContainsString('border-l-[3px] border-secondary', $contentDash);
        // Pastikan link surat masuk di halaman dashboard TIDAK aktif
        $this->assertMatchesRegularExpression('/href="[^"]*\/surat-masuk"[^>]*class="[^"]*text-on-surface-variant/', $contentDash);

        // 2. Kunjungi Surat Masuk Index
        $responseSuratMasuk = $this->actingAs($tu)->get(route('surat-masuk.index'));
        $responseSuratMasuk->assertOk();
        $contentSM = $responseSuratMasuk->getContent();

        // Di halaman Surat Masuk: link Surat Masuk memiliki class aktif
        $this->assertMatchesRegularExpression('/href="[^"]*\/surat-masuk"[^>]*class="[^"]*bg-primary-container text-primary border-l-\[3px\] border-secondary/', $contentSM);
        // Link Dashboard di halaman Surat Masuk TIDAK aktif (tidak memiliki border-secondary)
        $this->assertMatchesRegularExpression('/href="[^"]*\/dashboard\/tata-usaha"[^>]*class="[^"]*text-on-surface-variant/', $contentSM);

        // 3. Kunjungi Surat Keluar Index
        $responseSuratKeluar = $this->actingAs($tu)->get(route('surat-keluar.index'));
        $responseSuratKeluar->assertOk();
        $contentSK = $responseSuratKeluar->getContent();

        // Di halaman Surat Keluar: link Surat Keluar memiliki class aktif
        $this->assertMatchesRegularExpression('/href="[^"]*\/surat-keluar"[^>]*class="[^"]*bg-primary-container text-primary border-l-\[3px\] border-secondary/', $contentSK);
        // Link Dashboard & Surat Masuk TIDAK aktif
        $this->assertMatchesRegularExpression('/href="[^"]*\/dashboard\/tata-usaha"[^>]*class="[^"]*text-on-surface-variant/', $contentSK);
        $this->assertMatchesRegularExpression('/href="[^"]*\/surat-masuk"[^>]*class="[^"]*text-on-surface-variant/', $contentSK);
    }

    public function test_kepala_sekolah_analytics_page_renders_successfully(): void
    {
        $kepsek = User::factory()->create(['role' => Role::KEPALA_SEKOLAH]);

        $response = $this->actingAs($kepsek)->get(route('dashboard.kepala-sekolah.analytics'));
        $response->assertOk();
        $response->assertSee('analyticsWeeklyChart');
        $response->assertSee('analyticsCategoryChart');
    }
}

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
}

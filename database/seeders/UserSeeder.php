<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder ini HANYA boleh jalan di environment local
        if (! app()->environment('local')) {
            return;
        }

        $password = Hash::make('archiva123');

        $users = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'admin@archiva.test',
                'password' => $password,
                'role' => Role::ADMIN,
                'unit' => null,
            ],
            [
                'name' => 'Dra. Siti Nurhaliza, M.Pd.',
                'email' => 'kepsek@archiva.test',
                'password' => $password,
                'role' => Role::KEPALA_SEKOLAH,
                'unit' => null,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'tu@archiva.test',
                'password' => $password,
                'role' => Role::TATA_USAHA,
                'unit' => 'Dapodik',
            ],
            [
                'name' => 'Ibu Ratna Dewi, S.Pd.',
                'email' => 'guru@archiva.test',
                'password' => $password,
                'role' => Role::GURU,
                'unit' => null,
            ],
            [
                'name' => 'Pak Joko',
                'email' => 'tendik@archiva.test',
                'password' => $password,
                'role' => Role::TENAGA_PENDIDIK,
                'unit' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

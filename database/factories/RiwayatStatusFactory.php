<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\StatusSurat;
use App\Models\RiwayatStatus;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiwayatStatus>
 */
class RiwayatStatusFactory extends Factory
{
    protected $model = RiwayatStatus::class;

    public function definition(): array
    {
        return [
            'surat_masuk_id' => SuratMasuk::factory(),
            'dari_status' => null,
            'ke_status' => StatusSurat::DITERIMA->value,
            'user_id' => User::factory()->state(['role' => Role::TATA_USAHA]),
            'catatan' => fake()->sentence(),
            'created_at' => now(),
        ];
    }
}

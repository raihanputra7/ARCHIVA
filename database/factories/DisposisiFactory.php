<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disposisi>
 */
class DisposisiFactory extends Factory
{
    protected $model = Disposisi::class;

    public function definition(): array
    {
        return [
            'surat_masuk_id' => SuratMasuk::factory(),
            'dari_user_id' => User::factory()->state(['role' => Role::KEPALA_SEKOLAH]),
            'ke_user_id' => User::factory()->state(['role' => Role::GURU]),
            'from_user_id' => function (array $attributes) {
                return $attributes['dari_user_id'];
            },
            'to_user_id' => function (array $attributes) {
                return $attributes['ke_user_id'];
            },
            'instruksi' => fake()->sentence(8),
            'sifat' => fake()->randomElement(['Biasa', 'Segera', 'Kilat', 'Rahasia']),
            'batas_waktu' => fake()->dateTimeBetween('+1 days', '+7 days')->format('Y-m-d'),
            'status' => 'menunggu',
            'status_tindak_lanjut' => 'menunggu',
            'catatan_tindak_lanjut' => null,
            'selesai_pada' => null,
        ];
    }
}

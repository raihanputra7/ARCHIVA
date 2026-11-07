<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\StatusSurat;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SuratMasuk>
 */
class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2025, 2026);
        $number = str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
        $kategoriList = array_keys(config('archiva.kategori_surat', ['Dinas' => 'Dinas']));
        $sifatList = ['Biasa', 'Segera', 'Kilat', 'Rahasia'];
        $asalList = ['eksternal', 'internal'];

        return [
            'nomor_agenda' => "ARC/SM/{$year}/{$number}",
            'nomor_surat' => fake()->boolean(80) ? fake()->bothify('###/?-??/SDN07/2026') : null,
            'nomor_surat_asli' => fake()->boolean(80) ? fake()->bothify('###/?-??/SDN07/2026') : null,
            'pengirim' => fake()->company(),
            'perihal' => fake()->sentence(6),
            'ringkasan' => fake()->paragraph(),
            'catatan' => fake()->sentence(),
            'tanggal_surat' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'tanggal_diterima' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'asal' => fake()->randomElement($asalList),
            'kategori' => fake()->randomElement($kategoriList),
            'sifat' => fake()->randomElement($sifatList),
            'status' => fake()->randomElement(StatusSurat::values()),
            'file_path' => null,
            'file_name' => null,
            'file_mime' => null,
            'file_size' => null,
            'created_by' => User::factory()->state(['role' => Role::TATA_USAHA]),
            'dicatat_oleh' => User::factory()->state(['role' => Role::TATA_USAHA]),
        ];
    }
}

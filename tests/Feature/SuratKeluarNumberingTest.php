<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Services\SuratKeluarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratKeluarNumberingTest extends TestCase
{
    use RefreshDatabase;

    protected User $tu;

    protected SuratKeluarService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tu = User::firstOrCreate(
            ['email' => 'tu_numbering@archiva.test'],
            ['name' => 'TU Penomoran', 'password' => bcrypt('password'), 'role' => Role::TATA_USAHA]
        );

        $this->service = app(SuratKeluarService::class);
    }

    public function test_auto_numbering_starts_at_125_pk_01_01_for_start_year(): void
    {
        $this->assertEquals(125, $this->service->getNextNomorUrut(2026));
        $this->assertEquals('125/PK.01.01', $this->service->generateNomorSurat('PK.01.01', 2026));

        $response = $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'kode_klasifikasi' => 'PK.01.01',
            'tujuan' => 'Suku Dinas Pendidikan Wilayah II Jakarta Barat',
            'perihal' => 'Laporan Data Pokok Peserta Didik SD',
            'tanggal_surat' => '2026-11-12',
            'kategori' => 'Laporan',
            'sifat' => 'Biasa',
        ]);

        $surat = SuratKeluar::where('nomor_surat', '125/PK.01.01')->first();
        $this->assertNotNull($surat);
        $this->assertEquals('PK.01.01', $surat->kode_klasifikasi);
        $this->assertEquals('Sekolah Dasar', $surat->nama_klasifikasi);
        $response->assertRedirect(route('surat-keluar.show', $surat));
    }

    public function test_sequential_increment_continues_across_different_kepgub_classifications(): void
    {
        // Simulasikan tahap saat ini sudah di 125/PK.01.01
        $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'kode_klasifikasi' => 'PK.01.01',
            'nomor_surat' => '125/PK.01.01',
            'tujuan' => 'Sudin Pendidikan Wilayah II',
            'perihal' => 'Laporan BOS Tahap II',
            'tanggal_surat' => '2026-11-10',
            'kategori' => 'Laporan',
            'sifat' => 'Biasa',
        ]);

        // Surat berikutnya (mis. Surat Tugas Kepegawaian KG.11.00) otomatis menjadi 126/KG.11.00
        $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'kode_klasifikasi' => 'KG.11.00',
            'tujuan' => 'Guru Kelas IV SDN Jatipulo 07',
            'perihal' => 'Surat Tugas Workshop Kurikulum Merdeka',
            'tanggal_surat' => '2026-11-12',
            'kategori' => 'SK Tugas',
            'sifat' => 'Segera',
        ]);

        $surat126 = SuratKeluar::where('nomor_surat', '126/KG.11.00')->first();
        $this->assertNotNull($surat126);
        $this->assertEquals('KG.11.00', $surat126->kode_klasifikasi);
        $this->assertEquals('Surat Perintah Dinas / Surat Tugas', $surat126->nama_klasifikasi);

        // Surat berikutnya lagi dengan PK.02.00 otomatis menjadi 127/PK.02.00
        $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'kode_klasifikasi' => 'PK.02.00',
            'tujuan' => 'Pengawas Bina Sekolah Dasar',
            'perihal' => 'Rekap Data Sertifikasi Guru',
            'tanggal_surat' => '2026-11-13',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
        ]);

        $this->assertDatabaseHas('surat_keluar', [
            'kode_klasifikasi' => 'PK.02.00',
            'nomor_surat' => '127/PK.02.00',
        ]);
    }

    public function test_manual_kepgub_number_extracts_classification_code_when_not_provided(): void
    {
        $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'nomor_surat' => '128/DL.01.02',
            'tujuan' => 'Pusat Pengembangan Kompetensi Pendidik Jakarta Barat',
            'perihal' => 'Permohonan Peserta Diklat',
            'tanggal_surat' => '2026-11-14',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
        ]);

        $this->assertDatabaseHas('surat_keluar', [
            'nomor_surat' => '128/DL.01.02',
            'kode_klasifikasi' => 'DL.01.02',
        ]);
    }

    public function test_invalid_classification_code_is_rejected(): void
    {
        $response = $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'kode_klasifikasi' => 'INVALID-KODE',
            'tujuan' => 'Sudin Pendidikan',
            'perihal' => 'Uji Validasi',
            'tanggal_surat' => '2026-11-12',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
        ]);

        $response->assertSessionHasErrors('kode_klasifikasi');
    }

    public function test_surat_keluar_views_display_kepgub_classification_and_numbering(): void
    {
        $surat = $this->service->register([
            'kode_klasifikasi' => 'PK.01.01',
            'nomor_surat' => '125/PK.01.01',
            'tujuan' => 'Komite Sekolah SDN Jatipulo 07',
            'perihal' => 'Undangan Rapat RKAS Tahun 2026',
            'tanggal_surat' => '2026-11-12',
            'kategori' => 'Undangan',
            'sifat' => 'Biasa',
        ], null, $this->tu);

        $indexResponse = $this->actingAs($this->tu)->get(route('surat-keluar.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('125/PK.01.01');
        $indexResponse->assertSee('PK.01.01');
        $indexResponse->assertSee('Sekolah Dasar');
        $indexResponse->assertSee('126/PK.01.01');

        $showResponse = $this->actingAs($this->tu)->get(route('surat-keluar.show', $surat));
        $showResponse->assertOk();
        $showResponse->assertSee('125/PK.01.01');
        $showResponse->assertSee('PK.01.01');
        $showResponse->assertSee('Sekolah Dasar');
    }
}

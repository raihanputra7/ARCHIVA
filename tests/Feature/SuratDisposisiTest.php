<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratDisposisiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $kepsek;

    protected User $tu;

    protected User $guru;

    protected User $tendik;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@archiva.test'],
            ['name' => 'Admin Test', 'password' => bcrypt('password'), 'role' => Role::ADMIN]
        );

        $this->kepsek = User::firstOrCreate(
            ['email' => 'kepsek_test@archiva.test'],
            ['name' => 'Kepsek Test', 'password' => bcrypt('password'), 'role' => Role::KEPALA_SEKOLAH]
        );

        $this->tu = User::firstOrCreate(
            ['email' => 'tu_test@archiva.test'],
            ['name' => 'TU Test', 'password' => bcrypt('password'), 'role' => Role::TATA_USAHA]
        );

        $this->guru = User::firstOrCreate(
            ['email' => 'guru_test@archiva.test'],
            ['name' => 'Guru Test', 'password' => bcrypt('password'), 'role' => Role::GURU]
        );

        $this->tendik = User::firstOrCreate(
            ['email' => 'tendik_test@archiva.test'],
            ['name' => 'Tendik Test', 'password' => bcrypt('password'), 'role' => Role::TENAGA_PENDIDIK]
        );
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('surat-masuk.index'));
        $response->assertRedirect(route('login'));

        $response2 = $this->get(route('surat-keluar.index'));
        $response2->assertRedirect(route('login'));
    }

    public function test_tata_usaha_can_create_surat_masuk_with_file_upload(): void
    {
        Storage::fake('local');

        $fakePdf = UploadedFile::fake()->create('surat_edaran.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->tu)->post(route('surat-masuk.store'), [
            'nomor_surat_asli' => '005/TEST/2026',
            'pengirim' => 'Dinas Pendidikan DKI',
            'perihal' => 'Instruksi Penguatan Karakter',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'berkas' => $fakePdf,
        ]);

        $surat = SuratMasuk::where('nomor_surat_asli', '005/TEST/2026')->first();
        $this->assertNotNull($surat);
        $this->assertStringStartsWith('ARC/SM/', $surat->nomor_agenda);
        $this->assertEquals('Dinas', $surat->kategori);
        $this->assertEquals($this->tu->id, $surat->created_by);
        $this->assertNotNull($surat->file_path);

        Storage::disk('local')->assertExists($surat->file_path);

        $response->assertRedirect(route('surat-masuk.index'));
    }

    public function test_guru_cannot_create_surat_masuk(): void
    {
        $response = $this->actingAs($this->guru)->post(route('surat-masuk.store'), [
            'pengirim' => 'Pihak Luar',
            'perihal' => 'Pengujian Otorisasi',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
        ]);

        $response->assertForbidden();
    }

    public function test_kepala_sekolah_can_create_disposisi(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0001',
            'pengirim' => 'Sudin Jakbar',
            'perihal' => 'Persiapan Lomba Sains',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'kategori' => 'Lomba',
            'sifat' => 'Segera',
            'status' => 'Diterima',
            'created_by' => $this->tu->id,
        ]);

        $response = $this->actingAs($this->kepsek)
            ->from(route('surat-masuk.show', $surat))
            ->post(route('disposisi.store', $surat), [
                'to_user_id' => $this->guru->id,
                'instruksi' => 'Bimbing perwakilan siswa kelas 5.',
                'sifat' => 'Segera',
                'batas_waktu' => now()->addDays(3)->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('surat-masuk.show', $surat));

        $disposisi = Disposisi::where('surat_masuk_id', $surat->id)->first();
        $this->assertNotNull($disposisi);
        $this->assertEquals($this->kepsek->id, $disposisi->from_user_id);
        $this->assertEquals($this->guru->id, $disposisi->to_user_id);
        $this->assertEquals('Menunggu', $disposisi->status);

        // Status surat masuk berubah menjadi 'Didisposisikan'
        $surat->refresh();
        $this->assertEquals('Didisposisikan', $surat->status);
    }

    public function test_guru_cannot_create_disposisi(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0002',
            'pengirim' => 'Sudin Jakbar',
            'perihal' => 'Uji Otorisasi Disposisi',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'Diterima',
            'created_by' => $this->tu->id,
        ]);

        $response = $this->actingAs($this->guru)->post(route('disposisi.store', $surat), [
            'to_user_id' => $this->tendik->id,
            'instruksi' => 'Instruksi tidak berwenang',
            'sifat' => 'Biasa',
        ]);

        $response->assertForbidden();
    }

    public function test_assigned_user_can_update_tindak_lanjut_status(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0003',
            'pengirim' => 'Kementerian Dikdasmen',
            'perihal' => 'Pembaruan Dapodik',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'kategori' => 'Dinas',
            'sifat' => 'Penting',
            'status' => 'Didisposisikan',
            'created_by' => $this->tu->id,
        ]);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $surat->id,
            'from_user_id' => $this->kepsek->id,
            'to_user_id' => $this->guru->id,
            'instruksi' => 'Sinkronkan data kelas 1 sampai 6',
            'sifat' => 'Segera',
            'status' => 'Menunggu',
        ]);

        // Guru memperbarui status ke Diproses
        $response = $this->actingAs($this->guru)
            ->from(route('surat-masuk.show', $surat))
            ->patch(route('disposisi.update', $disposisi), [
                'status' => 'Diproses',
                'catatan_tindak_lanjut' => 'Proses sinkronisasi telah mencapai 80%.',
            ]);

        $response->assertRedirect(route('surat-masuk.show', $surat));
        $disposisi->refresh();
        $this->assertEquals('Diproses', $disposisi->status);
        $this->assertEquals('Proses sinkronisasi telah mencapai 80%.', $disposisi->catatan_tindak_lanjut);

        // Guru menyelesaikan tindak lanjut
        $response2 = $this->actingAs($this->guru)
            ->from(route('surat-masuk.show', $surat))
            ->patch(route('disposisi.update', $disposisi), [
                'status' => 'Selesai',
                'catatan_tindak_lanjut' => 'Sinkronisasi data Dapodik berhasil 100%.',
            ]);

        $response2->assertRedirect(route('surat-masuk.show', $surat));
        $disposisi->refresh();
        $this->assertEquals('Selesai', $disposisi->status);

        // Surat masuk ikut berstatus Selesai jika semua disposisi selesai
        $surat->refresh();
        $this->assertEquals('Selesai', $surat->status);
    }

    public function test_lembar_disposisi_renders_successfully(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0004',
            'nomor_surat_asli' => '421/099/TEST/2026',
            'pengirim' => 'Dinas Pendidikan DKI Jakarta',
            'perihal' => 'Undangan Rapat Koordinasi Kepala Sekolah',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'kategori' => 'Undangan',
            'sifat' => 'Penting',
            'status' => 'Didisposisikan',
            'created_by' => $this->tu->id,
        ]);

        Disposisi::create([
            'surat_masuk_id' => $surat->id,
            'from_user_id' => $this->kepsek->id,
            'to_user_id' => $this->guru->id,
            'instruksi' => 'Harap dampingi kepala sekolah ke lokasi rapat.',
            'sifat' => 'Penting',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->kepsek)->get(route('surat-masuk.lembar-disposisi', $surat));
        $response->assertOk();
        $response->assertSee('LEMBAR DISPOSISI');
        $response->assertSee('SDN JATIPULO 07');
        $response->assertSee('421/099/TEST/2026');
        $response->assertSee('Harap dampingi kepala sekolah');
    }

    public function test_surat_keluar_crud_and_approval_workflow(): void
    {
        // 1. TU membuat draf surat keluar
        $response = $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'nomor_surat' => '421.2/099/SDN07/2026',
            'tujuan' => 'Sudin Pendidikan Wilayah II Jakbar',
            'perihal' => 'Laporan Rekap Bulanan Siswa',
            'tanggal_surat' => '2026-11-05',
            'kategori' => 'Laporan',
            'sifat' => 'Biasa',
            'ringkasan' => 'Draf rekapitulasi data siswa aktif.',
        ]);

        $sk = SuratKeluar::where('nomor_surat', '421.2/099/SDN07/2026')->first();
        $this->assertNotNull($sk);
        $this->assertEquals('Draf', $sk->status);
        $response->assertRedirect(route('surat-keluar.show', $sk));

        // 2. Guru tidak bisa menyetujui surat keluar (403)
        $unauthApprove = $this->actingAs($this->guru)->patch(route('surat-keluar.approve', $sk));
        $unauthApprove->assertForbidden();

        // 3. Kepala Sekolah menyetujui surat keluar
        $authApprove = $this->actingAs($this->kepsek)->patch(route('surat-keluar.approve', $sk));
        $authApprove->assertRedirect(route('surat-keluar.show', $sk));

        $sk->refresh();
        $this->assertEquals('Disetujui', $sk->status);
        $this->assertEquals($this->kepsek->id, $sk->approved_by);
        $this->assertNotNull($sk->approved_at);
    }
}

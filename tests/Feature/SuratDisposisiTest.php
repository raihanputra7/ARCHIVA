<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\StatusSurat;
use App\Models\Disposisi;
use App\Models\RiwayatStatus;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\SuratMasukService;
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

    public function test_admin_cannot_access_surat_masuk_archive(): void
    {
        // Kebijakan hak akses: Admin tidak mengelola arsip surat dinas (hanya kelola pengguna)
        $response = $this->actingAs($this->admin)->get(route('surat-masuk.index'));
        $response->assertForbidden();

        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/2026/9999',
            'pengirim' => 'Disdik',
            'perihal' => 'Uji Akses Admin',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'diterima',
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        $responseShow = $this->actingAs($this->admin)->get(route('surat-masuk.show', $surat));
        $responseShow->assertForbidden();
    }

    public function test_tata_usaha_can_create_surat_masuk_with_file_upload(): void
    {
        Storage::fake('local');

        $fakePdf = UploadedFile::fake()->create('surat_edaran.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->tu)->post(route('surat-masuk.store'), [
            'nomor_surat' => '005/TEST/2026',
            'nomor_surat_asli' => '005/TEST/2026',
            'asal' => 'eksternal',
            'pengirim' => 'Dinas Pendidikan DKI',
            'perihal' => 'Instruksi Penguatan Karakter',
            'ringkasan' => 'Pelaksanaan program karakter budi pekerti semester ganjil.',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'penerima_ids' => [$this->kepsek->id],
            'berkas' => $fakePdf,
        ]);

        $surat = SuratMasuk::where('nomor_surat', '005/TEST/2026')->first();
        $this->assertNotNull($surat);
        $this->assertStringStartsWith('ARC/SM/', $surat->nomor_agenda);
        $this->assertEquals('Dinas', $surat->kategori);
        $this->assertEquals('eksternal', $surat->asal);
        $this->assertEquals($this->tu->id, $surat->created_by);
        $this->assertNotNull($surat->file_path);

        Storage::disk('local')->assertExists($surat->file_path);

        // Verifikasi tabel pivot surat_penerima
        $this->assertTrue($surat->penerima()->where('users.id', $this->kepsek->id)->exists());

        // Verifikasi baris pertama riwayat_status (status diterima)
        $riwayatPertama = RiwayatStatus::where('surat_masuk_id', $surat->id)->first();
        $this->assertNotNull($riwayatPertama);
        $this->assertEquals(StatusSurat::DITERIMA->value, $riwayatPertama->ke_status);
        $this->assertNull($riwayatPertama->dari_status);
        $this->assertEquals($this->tu->id, $riwayatPertama->user_id);

        $response->assertRedirect(route('surat-masuk.index'));
    }

    public function test_upload_validation_rejects_invalid_type_and_oversize(): void
    {
        Storage::fake('local');

        // 1. Ekstensi tidak diizinkan (misal: .exe atau .txt)
        $badFile = UploadedFile::fake()->create('malicious.exe', 100, 'application/x-msdownload');
        $responseBadExt = $this->actingAs($this->tu)->post(route('surat-masuk.store'), [
            'asal' => 'eksternal',
            'pengirim' => 'Pihak Luar',
            'perihal' => 'Uji File Salah',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'berkas' => $badFile,
        ]);
        $responseBadExt->assertSessionHasErrors('berkas');

        // 2. Ukuran melebihi batas 5 MB (misal 6 MB = 6144 KB)
        $oversizeFile = UploadedFile::fake()->create('big_doc.pdf', 6144, 'application/pdf');
        $responseOversize = $this->actingAs($this->tu)->post(route('surat-masuk.store'), [
            'asal' => 'eksternal',
            'pengirim' => 'Pihak Luar',
            'perihal' => 'Uji File Kebesaran',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'berkas' => $oversizeFile,
        ]);
        $responseOversize->assertSessionHasErrors('berkas');
    }

    public function test_penomoran_agenda_berurutan_per_tahun(): void
    {
        $service = app(SuratMasukService::class);

        $surat1 = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/2026/0001',
            'pengirim' => 'Disdik',
            'perihal' => 'Surat 1',
            'tanggal_surat' => '2026-01-01',
            'tanggal_diterima' => '2026-01-02',
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'diterima',
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        $nextNomor = $service->generateNomorAgenda(2026);
        $this->assertEquals('ARC/SM/2026/0002', $nextNomor);
    }

    public function test_guru_cannot_create_surat_masuk(): void
    {
        $response = $this->actingAs($this->guru)->post(route('surat-masuk.store'), [
            'asal' => 'internal',
            'pengirim' => 'Pihak Luar',
            'perihal' => 'Pengujian Otorisasi',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
        ]);

        $response->assertForbidden();
    }

    public function test_status_terlambat_dihitung_dari_batas_waktu_lewat(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/2026/0099',
            'pengirim' => 'Inspektorat',
            'perihal' => 'Uji Kasus Terlambat',
            'tanggal_surat' => now()->subDays(10),
            'tanggal_diterima' => now()->subDays(8),
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Kilat',
            'status' => StatusSurat::DIPROSES->value,
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        // Disposisi dengan batas waktu kemarin (sudah lewat) dan status masih diproses
        $disp = Disposisi::create([
            'surat_masuk_id' => $surat->id,
            'dari_user_id' => $this->kepsek->id,
            'ke_user_id' => $this->guru->id,
            'from_user_id' => $this->kepsek->id,
            'to_user_id' => $this->guru->id,
            'instruksi' => 'Harap selesaikan segera',
            'sifat' => 'Kilat',
            'batas_waktu' => now()->subDays(2),
            'status' => 'diproses',
            'status_tindak_lanjut' => 'diproses',
        ]);

        $this->assertTrue($surat->isTerlambat());
        $this->assertTrue($disp->isTerlambat());

        // Jika status surat atau disposisi telah selesai, tidak lagi dianggap terlambat
        $disp->update(['status_tindak_lanjut' => 'selesai', 'status' => 'selesai']);
        $surat->update(['status' => StatusSurat::SELESAI->value]);

        $this->assertFalse($surat->isTerlambat());
        $this->assertFalse($disp->isTerlambat());
    }

    public function test_kepala_sekolah_can_create_disposisi(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0001',
            'pengirim' => 'Sudin Jakbar',
            'perihal' => 'Persiapan Lomba Sains',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
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

        // Status surat masuk berubah menjadi 'didisposisikan'
        $surat->refresh();
        $this->assertEquals('didisposisikan', $surat->status);
    }

    public function test_multi_assignee_disposisi_creation_and_audit_trail(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0002',
            'pengirim' => 'Sudin Jakbar',
            'perihal' => 'Pemeriksaan Kesehatan Anak Sekolah',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'diterima',
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        // Kepala sekolah mendisposisikan secara jamak (multi-assignee) ke Guru dan Tendik
        $response = $this->actingAs($this->kepsek)->post(route('disposisi.store', $surat), [
            'to_user_ids' => [$this->guru->id, $this->tendik->id],
            'instruksi' => 'Koordinasikan jadwal pemeriksaan dengan guru kelas dan UKS.',
            'sifat' => 'Segera',
            'batas_waktu' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $response->assertRedirect();

        // Memastikan 2 record Disposisi dibuat untuk masing-masing penerima
        $this->assertEquals(2, Disposisi::where('surat_masuk_id', $surat->id)->count());

        $this->assertDatabaseHas('disposisi', [
            'surat_masuk_id' => $surat->id,
            'to_user_id' => $this->guru->id,
            'instruksi' => 'Koordinasikan jadwal pemeriksaan dengan guru kelas dan UKS.',
            'sifat' => 'Segera',
            'status' => 'Menunggu',
        ]);

        $this->assertDatabaseHas('disposisi', [
            'surat_masuk_id' => $surat->id,
            'to_user_id' => $this->tendik->id,
            'instruksi' => 'Koordinasikan jadwal pemeriksaan dengan guru kelas dan UKS.',
            'sifat' => 'Segera',
            'status' => 'Menunggu',
        ]);

        // Status surat berubah menjadi didisposisikan
        $surat->refresh();
        $this->assertEquals('didisposisikan', $surat->status);

        // RiwayatStatus mencatat baris transisi disposisi
        $this->assertDatabaseHas('riwayat_status', [
            'surat_masuk_id' => $surat->id,
            'ke_status' => StatusSurat::DIDISPOSISIKAN->value,
            'user_id' => $this->kepsek->id,
        ]);
    }

    public function test_tu_and_authorized_guru_can_create_disposisi_while_unauthorized_is_forbidden(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0002B',
            'pengirim' => 'Sudin Jakbar',
            'perihal' => 'Koordinasi Penilaian Semester',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'status' => 'Diterima',
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        // 1. TU berwenang membuat disposisi
        $responseTU = $this->actingAs($this->tu)->post(route('disposisi.store', $surat), [
            'to_user_ids' => [$this->guru->id],
            'instruksi' => 'Harap ditindaklanjuti untuk guru kelas.',
            'sifat' => 'Biasa',
        ]);
        $responseTU->assertRedirect();

        // 2. Guru yang terdaftar di disposisi dapat meneruskan instruksi ke rekan Tendik
        $responseGuru = $this->actingAs($this->guru)->post(route('disposisi.store', $surat), [
            'to_user_ids' => [$this->tendik->id],
            'instruksi' => 'Bantu rekapitulasi data nilai.',
            'sifat' => 'Biasa',
        ]);
        $responseGuru->assertRedirect();

        // 3. Tendik tidak memiliki wewenang membuat disposisi (403)
        $responseTendik = $this->actingAs($this->tendik)->post(route('disposisi.store', $surat), [
            'to_user_ids' => [$this->guru->id],
            'instruksi' => 'Instruksi tidak sah dari tendik',
            'sifat' => 'Biasa',
        ]);
        $responseTendik->assertForbidden();
    }

    public function test_assigned_user_can_update_tindak_lanjut_status(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0003',
            'pengirim' => 'Kementerian Dikdasmen',
            'perihal' => 'Pembaruan Dapodik',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
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
        $this->assertEquals('selesai', $surat->status);
    }

    public function test_lembar_disposisi_renders_successfully(): void
    {
        $surat = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/TEST/0004',
            'nomor_surat_asli' => '421/099/TEST/2026',
            'nomor_surat' => '421/099/TEST/2026',
            'pengirim' => 'Dinas Pendidikan DKI Jakarta',
            'perihal' => 'Undangan Rapat Koordinasi Kepala Sekolah',
            'tanggal_surat' => now(),
            'tanggal_diterima' => now(),
            'asal' => 'eksternal',
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

    public function test_surat_keluar_reply_linking_to_parent_surat_masuk(): void
    {
        // 1. Buat surat masuk
        $sm = SuratMasuk::create([
            'nomor_agenda' => 'ARC/SM/2026/0888',
            'nomor_surat_asli' => '005/UND/KEL/XI/2026',
            'nomor_surat' => '005/UND/KEL/XI/2026',
            'pengirim' => 'Kelurahan Jatipulo',
            'perihal' => 'Undangan Kerja Bakti Lingkungan Sekolah',
            'tanggal_surat' => '2026-11-01',
            'tanggal_diterima' => '2026-11-02',
            'asal' => 'eksternal',
            'kategori' => 'Undangan',
            'sifat' => 'Biasa',
            'status' => 'diterima',
            'created_by' => $this->tu->id,
            'dicatat_oleh' => $this->tu->id,
        ]);

        // 2. Akses index surat keluar dengan parameter parent_surat_masuk_id
        $responseIndex = $this->actingAs($this->tu)->get(route('surat-keluar.index', [
            'parent_surat_masuk_id' => $sm->id,
        ]));
        $responseIndex->assertOk();
        $responseIndex->assertSee('Balasan untuk Surat Masuk: ARC/SM/2026/0888');

        // 3. Simpan surat keluar yang mereferensikan parent_surat_masuk_id
        $responseStore = $this->actingAs($this->tu)->post(route('surat-keluar.store'), [
            'parent_surat_masuk_id' => $sm->id,
            'nomor_surat' => '421.2/100/SDN07/2026',
            'tujuan' => 'Kelurahan Jatipulo',
            'perihal' => 'Tanggapan atas: Undangan Kerja Bakti Lingkungan Sekolah',
            'tanggal_surat' => '2026-11-03',
            'kategori' => 'Dinas',
            'sifat' => 'Biasa',
            'ringkasan' => 'Konfirmasi partisipasi kerja bakti.',
        ]);

        $sk = SuratKeluar::where('nomor_surat', '421.2/100/SDN07/2026')->first();
        $this->assertNotNull($sk);
        $this->assertEquals($sm->id, $sk->parent_surat_masuk_id);
        $responseStore->assertRedirect(route('surat-keluar.show', $sk));

        // 4. Verifikasi relasi Eloquent 2 arah
        $this->assertEquals($sm->id, $sk->parentSuratMasuk->id);
        $this->assertTrue($sm->suratKeluar->contains($sk));
        $this->assertTrue($sm->suratBalasan->contains($sk));

        // 5. Verifikasi tampilan show surat keluar menampilkan rujukan ke surat masuk
        $responseShowSK = $this->actingAs($this->tu)->get(route('surat-keluar.show', $sk));
        $responseShowSK->assertOk();
        $responseShowSK->assertSee('Naskah Balasan Terkait');
        $responseShowSK->assertSee('ARC/SM/2026/0888');

        // 6. Verifikasi tampilan show surat masuk menampilkan rujukan ke surat keluar balasan
        $responseShowSM = $this->actingAs($this->tu)->get(route('surat-masuk.show', $sm));
        $responseShowSM->assertOk();
        $responseShowSM->assertSee('Surat Balasan Terkait');
        $responseShowSM->assertSee($sk->nomor_agenda);
        $responseShowSM->assertSee('Tanggapan atas: Undangan Kerja Bakti Lingkungan Sekolah');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Disposisi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $admin = User::where('email', 'admin@archiva.test')->first();
        $kepsek = User::where('email', 'kepsek@archiva.test')->first();
        $tu = User::where('email', 'tu@archiva.test')->first();
        $guru = User::where('email', 'guru@archiva.test')->first();
        $tendik = User::where('email', 'tendik@archiva.test')->first();

        if (! $tu || ! $kepsek || ! $guru || ! $tendik) {
            return;
        }

        $now = Carbon::now();

        // 1. Surat Masuk Dinas ANBK (Didisposisikan ke Guru)
        $surat1 = SuratMasuk::updateOrCreate(
            ['nomor_agenda' => 'ARC/SM/'.$now->format('Ym').'/0001'],
            [
                'nomor_surat_asli' => '421.2/1082/Disdik/X/2026',
                'pengirim' => 'Dinas Pendidikan Provinsi DKI Jakarta',
                'perihal' => 'Sosialisasi & Gladi Bersih Pelaksanaan ANBK Jenjang SD Tahun 2026',
                'tanggal_surat' => $now->copy()->subDays(2),
                'tanggal_diterima' => $now->copy()->subDays(2),
                'kategori' => 'Dinas',
                'sifat' => 'Penting',
                'status' => 'Didisposisikan',
                'catatan' => 'Berkas edaran resmi via Pos Elektronik Disdik',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::updateOrCreate(
            ['surat_masuk_id' => $surat1->id, 'to_user_id' => $guru->id],
            [
                'from_user_id' => $kepsek->id,
                'instruksi' => 'Tindak lanjuti koordinasi dengan proktor ruang IT lantai 3 dan persiapkan data siswa calon peserta.',
                'sifat' => 'Segera',
                'batas_waktu' => $now->copy()->addDays(3),
                'status' => 'Diproses',
                'catatan_tindak_lanjut' => 'Sedang menyusun daftar nominasi sementara siswa kelas 5.',
            ]
        );

        // 2. Surat Masuk Undangan Bimtek (Didisposisikan ke Guru)
        $surat2 = SuratMasuk::updateOrCreate(
            ['nomor_agenda' => 'ARC/SM/'.$now->format('Ym').'/0002'],
            [
                'nomor_surat_asli' => '005/341/BGP/2026',
                'pengirim' => 'Balai Guru Penggerak (BGP) DKI Jakarta',
                'perihal' => 'Undangan Bimbingan Teknis Pengelolaan Kinerja Guru Platform Merdeka Mengajar',
                'tanggal_surat' => $now->copy()->subDays(1),
                'tanggal_diterima' => $now->copy()->subDays(1),
                'kategori' => 'Undangan',
                'sifat' => 'Biasa',
                'status' => 'Didisposisikan',
                'catatan' => 'Daring via Zoom Meeting',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::updateOrCreate(
            ['surat_masuk_id' => $surat2->id, 'to_user_id' => $guru->id],
            [
                'from_user_id' => $kepsek->id,
                'instruksi' => 'Hadiri mewakili SDN Jatipulo 07 dan buatkan laporan singkat hasil bimtek.',
                'sifat' => 'Biasa',
                'batas_waktu' => $now->copy()->addDays(5),
                'status' => 'Menunggu',
            ]
        );

        // 3. Surat Masuk Kebersihan Gedung 4 Lantai (Didisposisikan ke Tendik)
        $surat3 = SuratMasuk::updateOrCreate(
            ['nomor_agenda' => 'ARC/SM/'.$now->format('Ym').'/0003'],
            [
                'nomor_surat_asli' => 'SK/042/SDN07/X/2026',
                'pengirim' => 'Kepala SDN Jatipulo 07 Jakarta',
                'perihal' => 'Instruksi Operasional & Pembagian Zona Kebersihan Gedung Baru 4 Lantai',
                'tanggal_surat' => $now->copy()->subDays(3),
                'tanggal_diterima' => $now->copy()->subDays(3),
                'kategori' => 'SK Tugas',
                'sifat' => 'Penting',
                'status' => 'Didisposisikan',
                'catatan' => 'Pelaksanaan harian lantai 1 sampai lantai 4',
                'created_by' => $tu->id,
            ]
        );

        Disposisi::updateOrCreate(
            ['surat_masuk_id' => $surat3->id, 'to_user_id' => $tendik->id],
            [
                'from_user_id' => $kepsek->id,
                'instruksi' => 'Pastikan kontrol kebersihan toilet lantai 1–4 dan lorong kelas diperiksa sebelum bel masuk sekolah.',
                'sifat' => 'Segera',
                'batas_waktu' => $now->copy()->addDays(1),
                'status' => 'Diproses',
                'catatan_tindak_lanjut' => 'Pengecekan fasilitas dan tempat sampah terpilah tiap lantai telah dijadwalkan.',
            ]
        );

        // 4. Surat Masuk Baru (Belum Disposisi)
        SuratMasuk::updateOrCreate(
            ['nomor_agenda' => 'ARC/SM/'.$now->format('Ym').'/0004'],
            [
                'nomor_surat_asli' => '045.2/210/Puskemas/2026',
                'pengirim' => 'Puskesmas Kelurahan Jatipulo',
                'perihal' => 'Jadwal Pemeriksaan Berkala Kesehatan dan Imunisasi Siswa Kelas 1–3',
                'tanggal_surat' => $now,
                'tanggal_diterima' => $now,
                'kategori' => 'Kesehatan',
                'sifat' => 'Biasa',
                'status' => 'Diterima',
                'catatan' => 'Menunggu verifikasi dan disposisi pimpinan',
                'created_by' => $tu->id,
            ]
        );

        // 5. Surat Keluar Disetujui
        SuratKeluar::updateOrCreate(
            ['nomor_agenda' => 'ARC/SK/'.$now->format('Ym').'/0001'],
            [
                'nomor_surat' => '421.2/012/SDN07/X/2026',
                'tujuan' => 'Suku Dinas Pendidikan Wilayah II Kota Administrasi Jakarta Barat',
                'perihal' => 'Laporan Rekapitulasi Data Kehadiran Pendidik dan Tenaga Kependidikan Bulan Oktober 2026',
                'tanggal_surat' => $now->copy()->subDay(),
                'kategori' => 'Laporan',
                'sifat' => 'Biasa',
                'status' => 'Disetujui',
                'ringkasan' => 'Lampiran rekap kehadiran bulanan guru ASN dan PPPK SDN Jatipulo 07 Jakarta.',
                'created_by' => $tu->id,
                'approved_by' => $kepsek->id,
                'approved_at' => $now->copy()->subDay(),
            ]
        );

        // 6. Surat Keluar Draf
        SuratKeluar::updateOrCreate(
            ['nomor_agenda' => 'ARC/SK/'.$now->format('Ym').'/0002'],
            [
                'nomor_surat' => null,
                'tujuan' => 'Pengurus Komite SDN Jatipulo 07 Jakarta Barat',
                'perihal' => 'Undangan Rapat Koordinasi Program Unggulan & Pembiasaan Karakter Berkelanjutan',
                'tanggal_surat' => $now,
                'kategori' => 'Undangan',
                'sifat' => 'Biasa',
                'status' => 'Draf',
                'ringkasan' => 'Konsep undangan rapat bersama komite sekolah di aula lantai 4.',
                'created_by' => $tu->id,
            ]
        );
    }
}

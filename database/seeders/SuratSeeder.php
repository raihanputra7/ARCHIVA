<?php

namespace Database\Seeders;

use App\Enums\StatusSurat;
use App\Models\Disposisi;
use App\Models\RiwayatStatus;
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

        $kepsek = User::where('email', 'kepsek@archiva.test')->first();
        $tu = User::where('email', 'tu@archiva.test')->first();
        $guru = User::where('email', 'guru@archiva.test')->first();
        $tendik = User::where('email', 'tendik@archiva.test')->first();

        if (! $tu || ! $kepsek || ! $guru || ! $tendik) {
            return;
        }

        $now = Carbon::now();
        $year = (int) $now->format('Y');

        $sampleLetters = [
            // Status DITERIMA (Baru masuk di TU, belum didisposisikan)
            [
                'asal' => 'eksternal',
                'pengirim' => 'Puskesmas Kelurahan Jatipulo',
                'nomor_surat' => '045.2/210/Pusk-JP/2026',
                'perihal' => 'Jadwal Pemeriksaan Berkala Kesehatan dan Penjaringan Siswa Baru Kelas 1',
                'ringkasan' => 'Pemberitahuan skrining kesehatan mata, gigi, dan status gizi anak sekolah tahun ajaran 2026.',
                'kategori' => 'Pemberitahuan',
                'sifat' => 'Biasa',
                'days_ago' => 0,
                'status' => StatusSurat::DITERIMA->value,
                'penerima_ids' => [$kepsek->id],
                'disposisi' => null,
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Kwartir Ranting Gerakan Pramuka Kecamatan Palmerah',
                'nomor_surat' => '024/PRAM-PLM/X/2026',
                'perihal' => 'Undangan Jambore Ranting Penggalang SD se-Kecamatan Palmerah',
                'ringkasan' => 'Kegiatan kepramukaan gabungan di Bumi Perkemahan Cibubur pada akhir pekan.',
                'kategori' => 'Undangan',
                'sifat' => 'Biasa',
                'days_ago' => 1,
                'status' => StatusSurat::DITERIMA->value,
                'penerima_ids' => [$kepsek->id],
                'disposisi' => null,
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Koordinator Komite SDN Jatipulo 07',
                'nomor_surat' => '003/KOMITE/SDN07/2026',
                'perihal' => 'Pengajuan Program Parenting & Sosialisasi Literasi Gizi Seimbang Siswa',
                'ringkasan' => 'Kolaborasi wali murid untuk kegiatan edukasi makan sehat di aula lantai 4 sekolah.',
                'kategori' => 'Permohonan',
                'sifat' => 'Biasa',
                'days_ago' => 1,
                'status' => StatusSurat::DITERIMA->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => null,
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Polsek Palmerah Jakarta Barat',
                'nomor_surat' => 'B/142/X/2026/Sek-Plm',
                'perihal' => 'Sosialisasi Program Keamanan Lingkungan Sekolah & Pencegahan Perundungan',
                'ringkasan' => 'Penyuluhan Kamtibmas bagi peserta didik fase B dan C SDN Jatipulo 07 Jakarta.',
                'kategori' => 'Dinas',
                'sifat' => 'Biasa',
                'days_ago' => 2,
                'status' => StatusSurat::DITERIMA->value,
                'penerima_ids' => [$kepsek->id],
                'disposisi' => null,
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Tim Adiwiyata & Pengelola Green School SDN Jatipulo 07',
                'nomor_surat' => '01/ADIWIYATA/SDN07/X/2026',
                'perihal' => 'Laporan Kebutuhan Bibit Tanaman Vertikal & Peremajaan Taman Lantai 2',
                'ringkasan' => 'Pengajuan sarana pendukung pemeliharaan lingkungan hijau gedung baru 4 lantai.',
                'kategori' => 'Lainnya',
                'sifat' => 'Biasa',
                'days_ago' => 2,
                'status' => StatusSurat::DITERIMA->value,
                'penerima_ids' => [$kepsek->id],
                'disposisi' => null,
            ],

            // Status DIDISPOSISIKAN (Sudah ada instruksi pimpinan, menunggu penerima memulai tindak lanjut)
            [
                'asal' => 'eksternal',
                'pengirim' => 'Balai Guru Penggerak (BGP) DKI Jakarta',
                'nomor_surat' => '005/341/BGP-DKI/2026',
                'perihal' => 'Undangan Bimbingan Teknis Pengelolaan Kinerja Guru Platform Merdeka Mengajar',
                'ringkasan' => 'Bimtek implementasi e-Kinerja PMM bagi guru kelas dan guru mapel jenjang SD.',
                'kategori' => 'Undangan',
                'sifat' => 'Biasa',
                'days_ago' => 3,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Hadiri mewakili SDN Jatipulo 07, ikuti seluruh sesi daring, dan susun resume hasil kegiatan.',
                    'sifat' => 'Biasa',
                    'batas_days' => 4,
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Kelurahan Jatipulo',
                'nomor_surat' => '440/120/Kel.JP/2026',
                'perihal' => 'Himbauan Kewaspadaan Dini Genangan Air & Kebersihan Saluran Lingkungan',
                'ringkasan' => 'Pembersihan saluran drainase lingkar luar sekolah mengantisipasi musim hujan.',
                'kategori' => 'Edaran',
                'sifat' => 'Segera',
                'days_ago' => 3,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Periksa seluruh talang air atap lantai 4 dan bak kontrol halaman sekolah.',
                    'sifat' => 'Segera',
                    'batas_days' => 2,
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Pembina Pramuka Siaga SDN Jatipulo 07',
                'nomor_surat' => '04/PRAM-SIAGA/2026',
                'perihal' => 'Permohonan Izin Latihan Gabungan Pesta Siaga Se-Gugus Palmerah',
                'ringkasan' => 'Pemanfaatan lapangan utama dan selasar lantai dasar untuk 6 barung pramuka.',
                'kategori' => 'Permohonan',
                'sifat' => 'Biasa',
                'days_ago' => 4,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Koordinasikan jadwal kegiatan agar tidak berbenturan dengan ekstrakurikuler lain.',
                    'sifat' => 'Biasa',
                    'batas_days' => 5,
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Kementerian Agama Kota Jakarta Barat',
                'nomor_surat' => 'B-310/Kemenag.01/2026',
                'perihal' => 'Pendataan Peserta Ujian Asesmen Pendidikan Agama Islam Jenjang Sekolah Dasar',
                'ringkasan' => 'Verifikasi nomor induk siswa nasional peserta asesmen PAI tahun 2026.',
                'kategori' => 'Dinas',
                'sifat' => 'Segera',
                'days_ago' => 4,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Validasi daftar nama siswa kelas 6 calon peserta bersama staf Dapodik.',
                    'sifat' => 'Segera',
                    'batas_days' => 3,
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Pengelola UKS & Dokter Kecil SDN Jatipulo 07',
                'nomor_surat' => '02/UKS/SDN07/2026',
                'perihal' => 'Usulan Pengadaan Kotak P3K Tambahan untuk Ruang Praktik Lantai 3',
                'ringkasan' => 'Kelengkapan pertolongan pertama pada laboratorium komputer dan ruang seni.',
                'kategori' => 'Permohonan',
                'sifat' => 'Biasa',
                'days_ago' => 5,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => [
                    'to' => $tu->id,
                    'instruksi' => 'Cek alokasi anggaran belanja barang operasional BOS reguler periode ini.',
                    'sifat' => 'Biasa',
                    'batas_days' => 4,
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],

            // Status DIPROSES (Sedang berjalan pengerjaannya)
            [
                'asal' => 'eksternal',
                'pengirim' => 'Dinas Pendidikan Provinsi DKI Jakarta',
                'nomor_surat' => '421.2/1082/Disdik/X/2026',
                'perihal' => 'Sosialisasi & Gladi Bersih Pelaksanaan ANBK Jenjang SD Tahun 2026',
                'ringkasan' => 'Petunjuk teknis gladi resik ANBK, spesifikasi server lokal, dan jadwal simulasi.',
                'kategori' => 'Dinas',
                'sifat' => 'Kilat',
                'days_ago' => 5,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Tindak lanjuti koordinasi proktor ruang IT lantai 3 dan pastikan koneksi client stabil.',
                    'sifat' => 'Kilat',
                    'batas_days' => 2,
                    'status' => 'diproses',
                    'catatan' => 'Pengecekan jaringan 25 laptop client dan server ANBK sedang dilakukan bersama tim IT.',
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Kepala Perpustakaan SDN Jatipulo 07',
                'nomor_surat' => '05/PERPUS/SDN07/2026',
                'perihal' => 'Penyusunan Rencana Kebutuhan Buku Bacaan Literasi Pojok Kelas Lantai 1–4',
                'ringkasan' => 'Rotasi buku fiksi dan non-fiksi penunjang 15 menit membaca sebelum jam pertama.',
                'kategori' => 'SK Tugas',
                'sifat' => 'Biasa',
                'days_ago' => 6,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Rangkum masukan judul buku dari wali kelas 1 sampai 6.',
                    'sifat' => 'Biasa',
                    'batas_days' => 5,
                    'status' => 'diproses',
                    'catatan' => 'Daftar kebutuhan buku sudah terkumpul 80% dari perwakilan guru.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Suku Dinas Kesehatan Jakarta Barat',
                'nomor_surat' => '091/SDK-JB/X/2026',
                'perihal' => 'Monitoring Penerapan Kantin Sehat & Bebas Bahan Berbahaya',
                'ringkasan' => 'Verifikasi kebersihan kantin sekolah, air bersih, dan sertifikasi penjamah makanan.',
                'kategori' => 'Dinas',
                'sifat' => 'Segera',
                'days_ago' => 6,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Dampingi petugas uji sampel air dan cek kebersihan ventilasi area kantin.',
                    'sifat' => 'Segera',
                    'batas_days' => 3,
                    'status' => 'diproses',
                    'catatan' => 'Pemeriksaan sanitasi area kantin sedang berjalan hari ini.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Badan Penanggulangan Bencana Daerah (BPBD) DKI Jakarta',
                'nomor_surat' => '360/BPBD/X/2026',
                'perihal' => 'Simulasi Tanggap Bencana Gempa Bumi & Jalur Evakuasi Gedung Bertingkat',
                'ringkasan' => 'Pelatihan jalur tangga darurat lantai 4 menuju titik kumpul halaman sekolah.',
                'kategori' => 'Pemberitahuan',
                'sifat' => 'Segera',
                'days_ago' => 7,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Pastikan rambu jalur evakuasi di setiap koridor lantai terpasang jelas dan tidak terhalang.',
                    'sifat' => 'Segera',
                    'batas_days' => 4,
                    'status' => 'diproses',
                    'catatan' => 'Denah evakuasi di tangga timur dan barat telah selesai dipasang ulang.',
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Koordinator Ekstrakurikuler Seni & Budaya',
                'nomor_surat' => '07/EKSKUL/SDN07/2026',
                'perihal' => 'Persiapan Lomba Tari Tradisional FLS2N Tingkat Kecamatan',
                'ringkasan' => 'Jadwal latihan intensif di ruang serbaguna lantai 2 menjelang seleksi lomba.',
                'kategori' => 'Permohonan',
                'sifat' => 'Biasa',
                'days_ago' => 7,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Bimbing peserta dan lengkapi surat izin orang tua siswa.',
                    'sifat' => 'Biasa',
                    'batas_days' => 6,
                    'status' => 'diproses',
                    'catatan' => 'Surat izin orang tua telah ditandatangani dan gladi kotor sedang berlangsung.',
                ],
            ],

            // Status SELESAI (Tindak lanjut tuntas 100%)
            [
                'asal' => 'eksternal',
                'pengirim' => 'Sudin Pendidikan Wilayah II Jakarta Barat',
                'nomor_surat' => '421.1/890/Sudin.II/2026',
                'perihal' => 'Penyusunan Kalender Akademik Semester Genap Tahun Ajaran 2026/2027',
                'ringkasan' => 'Pedoman hari efektif belajar dan jadwal libur cuti bersama semester genap.',
                'kategori' => 'Dinas',
                'sifat' => 'Biasa',
                'days_ago' => 12,
                'status' => StatusSurat::SELESAI->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => [
                    'to' => $tu->id,
                    'instruksi' => 'Arsipkan dan sesuaikan jadwal kalender dinding sekolah pada papan informasi.',
                    'sifat' => 'Biasa',
                    'batas_days' => -4,
                    'status' => 'selesai',
                    'catatan' => 'Kalender akademik telah dibagikan ke seluruh wali kelas dan diunggah ke arsip TU.',
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Panitia Perayaan Bulan Bahasa SDN Jatipulo 07',
                'nomor_surat' => '01/BULAN-BAHASA/2026',
                'perihal' => 'Laporan Pertanggungjawaban Kegiatan Lomba Puisi & Mendongeng',
                'ringkasan' => 'Rekapitulasi juara lomba dan dokumentasi penyerahan piala di lapangan sekolah.',
                'kategori' => 'Lainnya',
                'sifat' => 'Biasa',
                'days_ago' => 14,
                'status' => StatusSurat::SELESAI->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Periksa kelengkapan berkas LPJ dan dokumentasikan pada web profil sekolah.',
                    'sifat' => 'Biasa',
                    'batas_days' => -5,
                    'status' => 'selesai',
                    'catatan' => 'LPJ disetujui pimpinan dan piagam penghargaan telah dibagikan kepada pemenang.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'PT PLN (Persero) ULP Palmerah',
                'nomor_surat' => '048/PLN-PLM/X/2026',
                'perihal' => 'Pemberitahuan Pemeliharaan Berkala Gardu Distribusi Listrik',
                'ringkasan' => 'Pemadaman terencana selama 2 jam di akhir pekan untuk perawatan transformator.',
                'kategori' => 'Pemberitahuan',
                'sifat' => 'Biasa',
                'days_ago' => 15,
                'status' => StatusSurat::SELESAI->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Matikan saklar perangkat IT dan pendingin udara sebelum jadwal pemeliharaan.',
                    'sifat' => 'Biasa',
                    'batas_days' => -8,
                    'status' => 'selesai',
                    'catatan' => 'Genset cadangan telah dicek dan aliran listrik kembali normal.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Kementerian Pendidikan Dasar dan Menengah RI',
                'nomor_surat' => '1192/C/DIKDAS/2026',
                'perihal' => 'Buku Panduan Teknis Asesmen Kompetensi Minimum (AKM) Kelas',
                'ringkasan' => 'Distribusi buku pedoman penilaian diagnostik kognitif dan non-kognitif.',
                'kategori' => 'Dinas',
                'sifat' => 'Biasa',
                'days_ago' => 18,
                'status' => StatusSurat::SELESAI->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Sebarkan softcopy dokumen ke grup pendidik kelas 4 dan 5.',
                    'sifat' => 'Biasa',
                    'batas_days' => -10,
                    'status' => 'selesai',
                    'catatan' => 'Seluruh guru kelas telah mengunduh panduan AKM dari portal internal.',
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Penanggung Jawab Sarpras SDN Jatipulo 07',
                'nomor_surat' => 'SK/042/SDN07/X/2026',
                'perihal' => 'Instruksi Operasional & Pembagian Zona Kebersihan Gedung Baru 4 Lantai',
                'ringkasan' => 'Jadwal piket harian tenaga kebersihan toilet lantai 1 sampai lantai 4.',
                'kategori' => 'SK Tugas',
                'sifat' => 'Segera',
                'days_ago' => 20,
                'status' => StatusSurat::SELESAI->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Pastikan kontrol kebersihan lorong dan tempat sampah terpilah tiap lantai.',
                    'sifat' => 'Segera',
                    'batas_days' => -14,
                    'status' => 'selesai',
                    'catatan' => 'Kartu kontrol kebersihan di setiap lantai telah dipasang dan ditandatangani harian.',
                ],
            ],

            // KASUS TERLAMBAT (Batas waktu telah lewat tetapi status belum selesai)
            [
                'asal' => 'eksternal',
                'pengirim' => 'Suku Dinas Komunikasi, Informatika dan Statistik Jakbar',
                'nomor_surat' => '555/082/Sudin.Kominfotik/2026',
                'perihal' => 'Pemutakhiran Data Inventaris Jaringan Internet & Titik Akses Wi-Fi Sekolah',
                'ringkasan' => 'Pendataan router dan bandwidth jaringan fiber optik penunjang Smart School DKI.',
                'kategori' => 'Dinas',
                'sifat' => 'Kilat',
                'days_ago' => 8,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => [
                    'to' => $tu->id,
                    'instruksi' => 'Isi formulir digital rekap router lantai 1 sampai lantai 4 paling lambat kemarin.',
                    'sifat' => 'Kilat',
                    'batas_days' => -2, // Terlambat 2 hari!
                    'status' => 'diproses',
                    'catatan' => 'Sedang memeriksa nomor seri access point di ruang laboratorium lantai 3.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Inspektorat Pembantu Kota Administrasi Jakarta Barat',
                'nomor_surat' => '700/019/Irbanko.JB/2026',
                'perihal' => 'Uji Petik Tindak Lanjut Rekomendasi Hasil Pemeriksaan Dana BOS Triwulan III',
                'ringkasan' => 'Kelengkapan bukti kuitansi belanja modal perbaikan meja kursi siswa.',
                'kategori' => 'Dinas',
                'sifat' => 'Kilat',
                'days_ago' => 10,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => [
                    'to' => $tu->id,
                    'instruksi' => 'Siapkan arsip SPJ asli dan berita acara serah terima barang untuk verifikasi.',
                    'sifat' => 'Kilat',
                    'batas_days' => -3, // Terlambat 3 hari!
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Tim Pengelola Kinerja Guru SDN Jatipulo 07',
                'nomor_surat' => '08/PKG/SDN07/2026',
                'perihal' => 'Pengumpulan Lembar Observasi Praktik Pembelajaran Siklus I',
                'ringkasan' => 'Dokumen rubrik penilaian modul ajar kurikulum merdeka kelas rendah.',
                'kategori' => 'SK Tugas',
                'sifat' => 'Segera',
                'days_ago' => 11,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $guru->id],
                'disposisi' => [
                    'to' => $guru->id,
                    'instruksi' => 'Unggah nilai hasil observasi kelas 1–3 ke formulir penilaian sekolah.',
                    'sifat' => 'Segera',
                    'batas_days' => -4, // Terlambat 4 hari!
                    'status' => 'diproses',
                    'catatan' => 'Masih menunggu revisi rubrik dari guru kelas 2 B.',
                ],
            ],
            [
                'asal' => 'eksternal',
                'pengirim' => 'Badan Penyelenggara Jaminan Sosial (BPJS) Ketenagakerjaan',
                'nomor_surat' => 'B/671/BPJS-TK/2026',
                'perihal' => 'Rekonsiliasi Data Kepesertaan Tenaga Non-ASN SDN Jatipulo 07',
                'ringkasan' => 'Pencocokan data NIK dan nomor rekening pegawai honorer dan penjaga sekolah.',
                'kategori' => 'Pemberitahuan',
                'sifat' => 'Biasa',
                'days_ago' => 13,
                'status' => StatusSurat::DIDISPOSISIKAN->value,
                'penerima_ids' => [$kepsek->id, $tu->id],
                'disposisi' => [
                    'to' => $tu->id,
                    'instruksi' => 'Sinkronkan dengan data gaji komite bulan Oktober.',
                    'sifat' => 'Biasa',
                    'batas_days' => -1, // Terlambat 1 hari!
                    'status' => 'menunggu',
                    'catatan' => null,
                ],
            ],
            [
                'asal' => 'internal',
                'pengirim' => 'Koordinator Piket Keamanan Sekolah',
                'nomor_surat' => '01/SECURITY/SDN07/2026',
                'perihal' => 'Laporan Rekap Kunjungan Tamu Dinas & Buku Tamu Masuk Gerbang Utama',
                'ringkasan' => 'Pendataan kartu identitas tamu dan sterilisasi kendaraan roda dua di halaman sekolah.',
                'kategori' => 'Laporan',
                'sifat' => 'Biasa',
                'days_ago' => 14,
                'status' => StatusSurat::DIPROSES->value,
                'penerima_ids' => [$kepsek->id, $tendik->id],
                'disposisi' => [
                    'to' => $tendik->id,
                    'instruksi' => 'Rekap buku tamu mingguan dan serahkan laporannya ke TU.',
                    'sifat' => 'Biasa',
                    'batas_days' => -2, // Terlambat 2 hari!
                    'status' => 'diproses',
                    'catatan' => 'Rekap buku tamu bulan Oktober sedang dipindahkan ke buku induk.',
                ],
            ],
        ];

        // Jalankan seeding 25 surat
        foreach ($sampleLetters as $index => $item) {
            $numberStr = str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            $nomorAgenda = "ARC/SM/{$year}/{$numberStr}";

            $tanggalDiterima = $now->copy()->subDays($item['days_ago']);
            $tanggalSurat = $tanggalDiterima->copy()->subDays(rand(1, 3));

            $surat = SuratMasuk::updateOrCreate(
                ['nomor_agenda' => $nomorAgenda],
                [
                    'nomor_surat' => $item['nomor_surat'],
                    'nomor_surat_asli' => $item['nomor_surat'],
                    'pengirim' => $item['pengirim'],
                    'perihal' => $item['perihal'],
                    'ringkasan' => $item['ringkasan'],
                    'catatan' => $item['ringkasan'],
                    'tanggal_surat' => $tanggalSurat,
                    'tanggal_diterima' => $tanggalDiterima,
                    'asal' => $item['asal'],
                    'kategori' => $item['kategori'],
                    'sifat' => $item['sifat'],
                    'status' => $item['status'],
                    'created_by' => $tu->id,
                    'dicatat_oleh' => $tu->id,
                ]
            );

            // Hubungkan penerima (pivot: surat_penerima)
            $penerimaSyncData = [];
            foreach ($item['penerima_ids'] as $pId) {
                // Beri waktu dibaca acak jika statusnya bukan baru diterima
                $dibacaPada = $item['status'] !== StatusSurat::DITERIMA->value
                    ? $tanggalDiterima->copy()->addHours(rand(1, 6))
                    : null;
                $penerimaSyncData[$pId] = ['dibaca_pada' => $dibacaPada];
            }
            $surat->penerima()->sync($penerimaSyncData);

            // Hapus riwayat lama untuk menghindari duplikasi saat seeder dijalankan berulang
            RiwayatStatus::where('surat_masuk_id', $surat->id)->delete();

            // Baris 1: Status diterima
            RiwayatStatus::create([
                'surat_masuk_id' => $surat->id,
                'dari_status' => null,
                'ke_status' => StatusSurat::DITERIMA->value,
                'user_id' => $tu->id,
                'catatan' => 'Surat masuk dicatat oleh Tata Usaha ('.$tu->name.') dan diarsipkan ke dalam sistem.',
                'created_at' => $tanggalDiterima,
            ]);

            // Jika ada disposisi
            if (! empty($item['disposisi'])) {
                $disp = $item['disposisi'];
                $batasWaktu = $tanggalDiterima->copy()->addDays($disp['batas_days']);

                Disposisi::updateOrCreate(
                    [
                        'surat_masuk_id' => $surat->id,
                        'to_user_id' => $disp['to'],
                    ],
                    [
                        'from_user_id' => $kepsek->id,
                        'dari_user_id' => $kepsek->id,
                        'ke_user_id' => $disp['to'],
                        'instruksi' => $disp['instruksi'],
                        'sifat' => $disp['sifat'],
                        'batas_waktu' => $batasWaktu,
                        'status' => $disp['status'],
                        'status_tindak_lanjut' => $disp['status'],
                        'catatan_tindak_lanjut' => $disp['catatan'],
                        'selesai_pada' => $item['status'] === StatusSurat::SELESAI->value ? $batasWaktu : null,
                    ]
                );

                // Baris 2: Riwayat didisposisikan
                $tglDisposisi = $tanggalDiterima->copy()->addHours(2);
                RiwayatStatus::create([
                    'surat_masuk_id' => $surat->id,
                    'dari_status' => StatusSurat::DITERIMA->value,
                    'ke_status' => StatusSurat::DIDISPOSISIKAN->value,
                    'user_id' => $kepsek->id,
                    'catatan' => 'Kepala Sekolah memberikan instruksi disposisi: "'.$disp['instruksi'].'"',
                    'created_at' => $tglDisposisi,
                ]);

                // Baris 3: Jika diproses
                if (in_array($item['status'], [StatusSurat::DIPROSES->value, StatusSurat::SELESAI->value])) {
                    $tglDiproses = $tglDisposisi->copy()->addHours(3);
                    RiwayatStatus::create([
                        'surat_masuk_id' => $surat->id,
                        'dari_status' => StatusSurat::DIDISPOSISIKAN->value,
                        'ke_status' => StatusSurat::DIPROSES->value,
                        'user_id' => $disp['to'],
                        'catatan' => $disp['catatan'] ?? 'Penerima tugas memulai pelaksanaan tindak lanjut.',
                        'created_at' => $tglDiproses,
                    ]);
                }

                // Baris 4: Jika selesai
                if ($item['status'] === StatusSurat::SELESAI->value) {
                    $tglSelesai = $batasWaktu->copy();
                    RiwayatStatus::create([
                        'surat_masuk_id' => $surat->id,
                        'dari_status' => StatusSurat::DIPROSES->value,
                        'ke_status' => StatusSurat::SELESAI->value,
                        'user_id' => $disp['to'],
                        'catatan' => 'Tindak lanjut disposisi diselesaikan sepenuhnya.',
                        'created_at' => $tglSelesai,
                    ]);
                }
            }
        }

        // Pertahankan seed surat keluar sesuai tahap penomoran berjalan (125/PK.01.01)
        SuratKeluar::updateOrCreate(
            ['nomor_agenda' => 'ARC/SK/'.$year.'/0001'],
            [
                'kode_klasifikasi' => 'PK.01.01',
                'nomor_surat' => '125/PK.01.01',
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
    }
}

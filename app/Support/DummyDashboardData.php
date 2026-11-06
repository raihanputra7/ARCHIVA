<?php

namespace App\Support;

/**
 * Class DummyDashboardData
 *
 * DUMMY: ganti saat modul surat dibangun
 * Menyediakan data statistik tiruan, daftar surat dinas, riwayat aktivitas,
 * dan repositori arsip untuk 5 dashboard peran ARCHIVA.
 */
class DummyDashboardData
{
    /**
     * DUMMY: ganti saat modul surat dibangun
     */
    public static function getKpis(): array
    {
        return [
            [
                'id' => 'surat_masuk',
                'label' => 'Surat Masuk Terarsip',
                'value' => '91',
                'unit' => 'Berkas',
                'delta' => '+12%',
                'trend' => 'up',
                'subtext' => 'Bulan berjalan',
            ],
            [
                'id' => 'disposisi',
                'label' => 'Rata-rata Waktu Disposisi',
                'value' => '3.4',
                'unit' => 'Jam',
                'delta' => '-18%',
                'trend' => 'down',
                'subtext' => 'Lebih cepat dari target',
            ],
            [
                'id' => 'surat_keluar',
                'label' => 'Draf & Surat Keluar',
                'value' => '24',
                'unit' => 'Naskah',
                'delta' => '+8%',
                'trend' => 'up',
                'subtext' => 'Telah terverifikasi',
            ],
            [
                'id' => 'arsip_digital',
                'label' => 'Koleksi Berkas Digital',
                'value' => '142',
                'unit' => 'Dokumen',
                'delta' => '+5%',
                'trend' => 'up',
                'subtext' => 'Tersimpan di storage privat',
            ],
        ];
    }

    /**
     * DUMMY: ganti saat modul surat dibangun
     */
    public static function getRecentLetters(): array
    {
        return [
            [
                'id' => 'ARC/SM/202610/0001',
                'number' => '421.2/1082/Disdik/X/2026',
                'sender' => 'Dinas Pendidikan Provinsi DKI Jakarta',
                'subject' => 'Sosialisasi & Gladi Bersih Pelaksanaan ANBK Jenjang SD Tahun 2026',
                'date' => '20 Okt 2026',
                'received_at' => '20 Okt 2026, 09:30 WIB',
                'status' => 'Didisposisikan',
                'recipient' => 'Pak Ahmad Fauzi & Guru Kelas 5',
                'urgency' => 'Penting',
                'category' => 'Dinas',
            ],
            [
                'id' => 'ARC/SM/202610/0002',
                'number' => '005/341/BGP/2026',
                'sender' => 'Balai Guru Penggerak (BGP) DKI Jakarta',
                'subject' => 'Undangan Bimbingan Teknis Pengelolaan Kinerja Guru Platform Merdeka Mengajar',
                'date' => '20 Okt 2026',
                'received_at' => '20 Okt 2026, 11:00 WIB',
                'status' => 'Diterima',
                'recipient' => 'Ibu Ratna Dewi, S.Pd.',
                'urgency' => 'Biasa',
                'category' => 'Undangan',
            ],
            [
                'id' => 'ARC/SK/202610/0003',
                'number' => 'SK/042/SDN07/X/2026',
                'sender' => 'Kepala SDN Jatipulo 07 Jakarta',
                'subject' => 'Penetapan Pembagian Zona Tugas Kebersihan Gedung Baru 4 Lantai',
                'date' => '19 Okt 2026',
                'received_at' => '19 Okt 2026, 14:15 WIB',
                'status' => 'Selesai',
                'recipient' => 'Seluruh Tenaga Pendidik & Kependidikan',
                'urgency' => 'Penting',
                'category' => 'SK Tugas',
            ],
            [
                'id' => 'ARC/SM/202610/0004',
                'number' => '045.2/210/Puskemas/2026',
                'sender' => 'Puskesmas Kelurahan Jatipulo',
                'subject' => 'Jadwal Pemeriksaan Berkala Kesehatan dan Imunisasi Siswa Kelas 1–3',
                'date' => '18 Okt 2026',
                'received_at' => '18 Okt 2026, 10:20 WIB',
                'status' => 'Diproses',
                'recipient' => 'Koordinator UKS & Guru Kelas Rendah',
                'urgency' => 'Biasa',
                'category' => 'Kesehatan',
            ],
            [
                'id' => 'ARC/SM/202610/0005',
                'number' => '900/154/BOS/2026',
                'sender' => 'Seksi Dikdas & PKLK Jakarta Barat',
                'subject' => 'Verifikasi SPJ dan Kelengkapan Laporan Bantuan Operasional Sekolah Tahap II',
                'date' => '17 Okt 2026',
                'received_at' => '17 Okt 2026, 13:45 WIB',
                'status' => 'Selesai',
                'recipient' => 'Budi Santoso (TU Keuangan)',
                'urgency' => 'Penting',
                'category' => 'Keuangan',
            ],
        ];
    }

    /**
     * DUMMY: ganti saat modul surat dibangun
     */
    public static function getRepositories(): array
    {
        return [
            [
                'id' => 'repo-1',
                'name' => 'surat-masuk-dinas',
                'title' => 'Surat Masuk Dinas',
                'description' => 'Arsip persuratan resmi dari Dinas Pendidikan, Sudin, dan Kementerian.',
                'category' => 'Kedinasan',
                'files_count' => 42,
                'updated_at' => '10 menit lalu',
            ],
            [
                'id' => 'repo-2',
                'name' => 'sk-tugas-sekolah',
                'title' => 'SK & Surat Tugas',
                'description' => 'Koleksi Surat Keputusan Kepala Sekolah, panitia kegiatan, dan pembagian tugas.',
                'category' => 'Keputusan',
                'files_count' => 28,
                'updated_at' => '1 jam lalu',
            ],
            [
                'id' => 'repo-3',
                'name' => 'kurikulum-merdeka-2026',
                'title' => 'Kurikulum Merdeka 2026',
                'description' => 'Perangkat ajar, Capaian Pembelajaran, Alur Tujuan Pembelajaran, dan modul ajar.',
                'category' => 'Kurikulum',
                'files_count' => 65,
                'updated_at' => '3 jam lalu',
            ],
            [
                'id' => 'repo-4',
                'name' => 'spb-laporan-bos',
                'title' => 'SPJ & Laporan BOS',
                'description' => 'Surat Pertanggungjawaban (SPJ) dan verifikasi anggaran Bantuan Operasional Sekolah.',
                'category' => 'Keuangan',
                'files_count' => 37,
                'updated_at' => '5 jam lalu',
            ],
        ];
    }

    /**
     * DUMMY: ganti saat modul surat dibangun
     */
    public static function getAuditActivities(): array
    {
        return [
            [
                'action' => 'Registrasi Surat Masuk',
                'user' => 'Budi Santoso (Tata Usaha)',
                'detail' => 'Mencatat naskah 421.2/1082/Disdik/X/2026 Sosialisasi ANBK',
                'time' => '10 menit lalu',
                'icon' => 'mark_email_unread',
            ],
            [
                'action' => 'Disposisi Surat',
                'user' => 'Dra. Siti Nurhaliza, M.Pd. (Kepala Sekolah)',
                'detail' => 'Meneruskan instruksi pelaksanaan ANBK ke Pak Fauzi & Guru Kelas 5',
                'time' => '45 menit lalu',
                'icon' => 'rate_review',
            ],
            [
                'action' => 'Tindak Lanjut Tugas',
                'user' => 'Ibu Ratna Dewi, S.Pd. (Guru)',
                'detail' => 'Mengonfirmasi partisipasi Bimtek Platform Merdeka Mengajar',
                'time' => '2 jam lalu',
                'icon' => 'task_alt',
            ],
            [
                'action' => 'Pengarsipan Dokumen',
                'user' => 'Pak Joko (Tenaga Pendidik)',
                'detail' => 'Mengunduh panduan zona kebersihan gedung baru lantai 1–4',
                'time' => '3 jam lalu',
                'icon' => 'folder_open',
            ],
            [
                'action' => 'Verifikasi Sistem',
                'user' => 'Ahmad Fauzi (Admin)',
                'detail' => 'Pemeriksaan integritas audit trail dan hak akses peran berhasil',
                'time' => '5 jam lalu',
                'icon' => 'admin_panel_settings',
            ],
        ];
    }

    /**
     * DUMMY: ganti saat modul surat dibangun
     */
    public static function getChartData(): array
    {
        return [
            'trend' => [
                'labels' => ['14 Okt', '15 Okt', '16 Okt', '17 Okt', '18 Okt', '19 Okt', '20 Okt'],
                'data' => [4, 7, 5, 8, 11, 6, 14],
            ],
            'categories' => [
                'labels' => ['Dinas', 'Undangan', 'Kurikulum', 'Keuangan', 'Kesehatan'],
                'data' => [38, 24, 18, 12, 8],
            ],
        ];
    }
}

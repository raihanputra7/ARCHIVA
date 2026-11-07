<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Format Nomor Agenda Surat Masuk
    |--------------------------------------------------------------------------
    | Format nomor agenda otomatis per tahun.
    | Token yang tersedia:
    | {PREFIX} - Kode prefix modul surat (misal: ARC/SM)
    | {YEAR}   - 4 digit tahun penerimaan (misal: 2026)
    | {NUMBER} - Nomor urut berurutan dengan panjang digit pad_length
    */
    'nomor_agenda' => [
        'prefix' => 'ARC/SM',
        'format' => '{PREFIX}/{YEAR}/{NUMBER}',
        'pad_length' => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Batasan Berkas Surat Masuk (Shared Hosting Friendly)
    |--------------------------------------------------------------------------
    | Batas ukuran maksimal file upload (dalam Kilobytes). Default: 5120 KB (5 MB).
    */
    'upload' => [
        'max_size_kb' => (int) env('ARCHIVA_MAX_UPLOAD_KB', 5120),
        'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
        'disk' => 'local',
        'storage_path' => 'surat_masuk',
    ],

    /*
    |--------------------------------------------------------------------------
    | Opsi Domain Persuratan
    |--------------------------------------------------------------------------
    */
    'asal_surat' => [
        'eksternal' => 'Eksternal (Instansi Luar)',
        'internal' => 'Internal (Warga Sekolah)',
    ],

    'kategori_surat' => [
        'Dinas' => 'Surat Dinas / Kedinasan Resmi',
        'Undangan' => 'Surat Undangan Rapat / Kegiatan',
        'Edaran' => 'Surat Edaran',
        'Pemberitahuan' => 'Surat Pemberitahuan',
        'Permohonan' => 'Surat Permohonan / Kerjasama',
        'SK Tugas' => 'Surat Keputusan / Surat Tugas',
        'Lainnya' => 'Lainnya',
    ],

    'sifat_surat' => [
        'Biasa' => 'Biasa',
        'Segera' => 'Segera',
        'Kilat' => 'Kilat',
        'Rahasia' => 'Rahasia',
    ],
];

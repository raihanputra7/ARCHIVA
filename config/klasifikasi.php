<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Klasifikasi Arsip Persuratan (Kepgub DKI Jakarta No. 1598 Tahun 2021)
    |--------------------------------------------------------------------------
    | Daftar kode klasifikasi resmi yang relevan untuk persuratan SDN Jatipulo 07:
    | - IX. PK     : Pendidikan
    | - XXXV. KG   : Kepegawaian
    | - XXXVI. DL  : Pendidikan dan Pelatihan
    | - XXXVII. HK : Hukum
    */
    'groups' => [
        'PK' => [
            'label' => 'IX. PK — Pendidikan',
            'items' => [
                'PK.00.00' => [
                    'nama' => 'Kebijakan Bidang Pendidikan',
                    'keterangan' => 'Naskah terkait Penetapan Kebijakan (sejak Rancangan Hingga Penetapan), Referensi Kebijakan Provinsi.',
                ],
                'PK.00.01' => [
                    'nama' => 'Penelitian dan Kajian Kebijakan Pendidikan',
                    'keterangan' => 'Naskah terkait Penetapan Kebijakan Pendidikan dan kajian referensi kebijakan provinsi.',
                ],
                'PK.00.02' => [
                    'nama' => 'Perjanjian Kerjasama',
                    'keterangan' => 'Naskah terkait Perencanaan Hingga Pelaksanaan Perjanjian Kerja Sama bidang pendidikan.',
                ],
                'PK.00.03' => [
                    'nama' => 'Sertifikasi Lembaga, Akreditasi dan Audit',
                    'keterangan' => 'Naskah terkait Sertifikasi Lembaga, Akreditasi, dan Audit satuan pendidikan.',
                ],
                'PK.01.00' => [
                    'nama' => 'Pendidikan Anak Usia Dini (PAUD)',
                    'keterangan' => 'Naskah terkait TK, Kelompok Bermain, dan kemitraan PAUD.',
                ],
                'PK.01.01' => [
                    'nama' => 'Sekolah Dasar',
                    'keterangan' => 'Kelembagaan; Kurikulum; Program dan Evaluasi Pembinaan Sekolah Dasar; Penilaian; Peserta Didik; Sumber Belajar; dan Kemitraan SD.',
                ],
                'PK.01.02' => [
                    'nama' => 'Sekolah Menengah Pertama',
                    'keterangan' => 'Pembinaan, kurikulum, peserta didik, dan kemitraan SMP.',
                ],
                'PK.01.05' => [
                    'nama' => 'Pendidikan Masyarakat',
                    'keterangan' => 'Pendidikan Kesetaraan, Keaksaraan, Pendidikan Keluarga, Kursus dan Pelatihan.',
                ],
                'PK.01.06' => [
                    'nama' => 'Pendidikan Khusus / Layanan Khusus',
                    'keterangan' => 'Pembinaan, kurikulum, peserta didik, dan kemitraan Pendidikan Layanan Khusus.',
                ],
                'PK.02.00' => [
                    'nama' => 'Guru',
                    'keterangan' => 'Naskah terkait Sertifikasi Guru (NSPK, Data Sasaran dan Kelulusan) serta Kompetensi Guru.',
                ],
                'PK.02.01' => [
                    'nama' => 'Tenaga Kependidikan',
                    'keterangan' => 'Naskah terkait pembinaan dan administrasi Tenaga Kependidikan.',
                ],
                'PK.02.02' => [
                    'nama' => 'Kepala Satuan Pendidikan',
                    'keterangan' => 'Naskah terkait Kepala Satuan Pendidikan.',
                ],
                'PK.03.00' => [
                    'nama' => 'Bantuan Sosial Satuan Pendidikan',
                    'keterangan' => 'Naskah terkait Bantuan Sosial Satuan Pendidikan.',
                ],
                'PK.03.01' => [
                    'nama' => 'Bantuan Sosial Masyarakat',
                    'keterangan' => 'Naskah terkait Bantuan Sosial Masyarakat.',
                ],
                'PK.03.02' => [
                    'nama' => 'Bantuan Sosial Guru dan Tenaga Kependidikan',
                    'keterangan' => 'Naskah terkait Bantuan Sosial Guru dan Tenaga Kependidikan.',
                ],
                'PK.03.03' => [
                    'nama' => 'Penghargaan',
                    'keterangan' => 'Naskah terkait Penghargaan bidang pendidikan.',
                ],
                'PK.04.00' => [
                    'nama' => 'Pengolahan Data',
                    'keterangan' => 'Naskah terkait Pengolahan Data pendidikan.',
                ],
                'PK.04.01' => [
                    'nama' => 'Informasi dan Statistik',
                    'keterangan' => 'Naskah terkait Informasi dan Statistik pendidikan.',
                ],
                'PK.04.02' => [
                    'nama' => 'Perangkat Lunak',
                    'keterangan' => 'Naskah terkait Perangkat Lunak pendidikan.',
                ],
                'PK.04.03' => [
                    'nama' => 'Perangkat Keras',
                    'keterangan' => 'Naskah terkait Perangkat Keras pendidikan.',
                ],
                'PK.05.00' => [
                    'nama' => 'Prasarana Pendidikan',
                    'keterangan' => 'Naskah terkait Prasarana Pendidikan.',
                ],
                'PK.05.01' => [
                    'nama' => 'Sarana Pendidikan',
                    'keterangan' => 'Naskah terkait Sarana Pendidikan.',
                ],
            ],
        ],

        'KG' => [
            'label' => 'XXXV. KG — Kepegawaian',
            'items' => [
                'KG.00.00' => [
                    'nama' => 'Pengkajian, Pengusulan Kebijakan, dan Naskah Akademik',
                    'keterangan' => 'Naskah terkait pengkajian, pengusulan kebijakan, dan naskah akademik kepegawaian.',
                ],
                'KG.00.06' => [
                    'nama' => 'Memorandum of Understanding (Kepegawaian)',
                    'keterangan' => 'Naskah terkait Memorandum of Understanding bidang kepegawaian.',
                ],
                'KG.01.00' => [
                    'nama' => 'Perencanaan Kebutuhan Aparatur Sipil Negara (ASN)',
                    'keterangan' => 'Bahan penyusunan rencana dan analisis kebutuhan ASN.',
                ],
                'KG.02.00' => [
                    'nama' => 'Formasi Aparatur Sipil Negara (ASN)',
                    'keterangan' => 'Usulan formasi, persetujuan formasi, dan penetapan formasi ASN.',
                ],
                'KG.03.00' => [
                    'nama' => 'Alih Tugas Aparatur Sipil Negara (ASN)',
                    'keterangan' => 'Naskah terkait surat keputusan mutasi pegawai.',
                ],
                'KG.03.01' => [
                    'nama' => 'Kenaikan Pangkat / Golongan / Jabatan',
                    'keterangan' => 'Naskah terkait SK kenaikan pangkat, golongan, dan jabatan.',
                ],
                'KG.03.02' => [
                    'nama' => 'Pengangkatan dan Pemberhentian Jabatan Struktural/Fungsional',
                    'keterangan' => 'Naskah terkait SK pengangkatan dan pemberhentian jabatan.',
                ],
                'KG.03.03' => [
                    'nama' => 'Perubahan Data Dasar / Status / Kedudukan Hukum Pegawai',
                    'keterangan' => 'Naskah terkait perubahan data dasar, status, dan kedudukan hukum pegawai.',
                ],
                'KG.03.08' => [
                    'nama' => 'Pengelolaan Pegawai Non Aparatur Sipil Negara (ASN)',
                    'keterangan' => 'Naskah terkait pengelolaan pegawai Non-ASN / honorer / KKI.',
                ],
                'KG.04.00' => [
                    'nama' => 'Tugas Belajar / Ijin Belajar / Diklat / Kursus',
                    'keterangan' => 'Perencanaan, pelaksanaan, pelaporan, serta STTP/sertifikat diklat pegawai.',
                ],
                'KG.04.01' => [
                    'nama' => 'Penyesuaian Ijazah dan Peningkatan Pendidikan',
                    'keterangan' => 'Naskah terkait penyesuaian ijazah dan peningkatan pendidikan pegawai.',
                ],
                'KG.04.03' => [
                    'nama' => 'Rencana Kinerja, SKP dan Penilaian Prestasi Kerja',
                    'keterangan' => 'Naskah terkait rencana kinerja, Sistem Kinerja Pegawai (SKP), dan penilaian prestasi kerja.',
                ],
                'KG.04.04' => [
                    'nama' => 'Angka Kredit',
                    'keterangan' => 'Naskah terkait pengajuan daftar usul dan penilaian angka kredit guru/jabatan fungsional.',
                ],
                'KG.05.00' => [
                    'nama' => 'Penilaian Kinerja dan Standar Kerja',
                    'keterangan' => 'Sistem penilaian kinerja, standar kinerja jabatan, dan penilaian kinerja ASN.',
                ],
                'KG.06.00' => [
                    'nama' => 'Kode Etik Pegawai',
                    'keterangan' => 'Penetapan kode etik, penegakan kode etik, dan SK hukuman.',
                ],
                'KG.06.01' => [
                    'nama' => 'Disiplin Pegawai',
                    'keterangan' => 'Penegakan disiplin, peningkatan disiplin, dan SK hukuman disiplin.',
                ],
                'KG.06.03' => [
                    'nama' => 'Pensiun Aparatur Sipil Negara (ASN)',
                    'keterangan' => 'Administrasi, penetapan, pertimbangan teknis, dan SK pensiun ASN.',
                ],
                'KG.09.00' => [
                    'nama' => 'Pengolahan Data dan Informasi Kepegawaian',
                    'keterangan' => 'Pengolahan data dan informasi kepegawaian.',
                ],
                'KG.11.00' => [
                    'nama' => 'Surat Perintah Dinas / Surat Tugas',
                    'keterangan' => 'Naskah terkait surat perintah dinas dan surat tugas pegawai.',
                ],
                'KG.11.01' => [
                    'nama' => 'Cuti Sakit, Cuti Bersalin, Cuti Tahunan',
                    'keterangan' => 'Naskah terkait pengajuan dan persetujuan cuti sakit, bersalin, dan tahunan.',
                ],
                'KG.11.02' => [
                    'nama' => 'Cuti Alasan Penting',
                    'keterangan' => 'Naskah terkait cuti alasan penting pegawai.',
                ],
                'KG.11.03' => [
                    'nama' => 'Cuti Besar dan Cuti di Luar Tanggungan Negara',
                    'keterangan' => 'Naskah terkait cuti besar dan cuti di luar tanggungan negara.',
                ],
                'KG.11.04' => [
                    'nama' => 'Dokumen Identitas Pegawai',
                    'keterangan' => 'Karpeg, KPE, Karis, Karsu, kartu pengenal ASN, LP2P, dan KP4.',
                ],
                'KG.11.05' => [
                    'nama' => 'Berkas Kepegawaian dan Daftar Urut Kepangkatan (DUK)',
                    'keterangan' => 'Berkas kepegawaian dan Daftar Urut Kepangkatan (DUK).',
                ],
                'KG.11.06' => [
                    'nama' => 'Pengurusan Kenaikan Gaji Berkala, Mutasi Gaji/Tunjangan',
                    'keterangan' => 'Berkas pengurusan kenaikan gaji berkala (KGB) dan mutasi gaji/tunjangan.',
                ],
                'KG.12.01' => [
                    'nama' => 'Asuransi Pegawai / BPJS',
                    'keterangan' => 'Naskah terkait asuransi pegawai dan BPJS.',
                ],
                'KG.12.07' => [
                    'nama' => 'Pemberian Piagam Penghargaan dan Tanda Jasa',
                    'keterangan' => 'Piagam penghargaan, sertifikat, dan tanda jasa pegawai.',
                ],
                'KG.13.00' => [
                    'nama' => 'Pegawai Negeri Sipil (PNS)',
                    'keterangan' => 'Naskah terkait administrasi Pegawai Negeri Sipil (PNS).',
                ],
                'KG.13.01' => [
                    'nama' => 'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)',
                    'keterangan' => 'Naskah terkait administrasi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK).',
                ],
                'KG.15' => [
                    'nama' => 'KORPRI',
                    'keterangan' => 'Naskah terkait Korps Pegawai Republik Indonesia (KORPRI).',
                ],
            ],
        ],

        'DL' => [
            'label' => 'XXXVI. DL — Pendidikan dan Pelatihan',
            'items' => [
                'DL.00.00' => [
                    'nama' => 'Pengkajian dan Penyusunan Kebijakan Diklat',
                    'keterangan' => 'Naskah terkait pengkajian dan penyusunan kebijakan pendidikan dan pelatihan.',
                ],
                'DL.01.00' => [
                    'nama' => 'Pengembangan Program dan Pembinaan Diklat',
                    'keterangan' => 'Standarisasi, kurikulum/modul, evaluasi, konsultasi, dan asistensi diklat.',
                ],
                'DL.01.02' => [
                    'nama' => 'Penyelenggaraan Diklat',
                    'keterangan' => 'Perencanaan, penyelenggaraan, bahan ajar, evaluasi, dan alumni diklat.',
                ],
                'DL.01.03' => [
                    'nama' => 'Tugas Belajar',
                    'keterangan' => 'Seleksi, SK program, konsultasi, dan hasil tugas belajar.',
                ],
            ],
        ],

        'HK' => [
            'label' => 'XXXVII. HK — Hukum',
            'items' => [
                'HK.00.03' => [
                    'nama' => 'Norma Standar Prosedur Kriteria (NSPK)',
                    'keterangan' => 'Naskah terkait Norma Standar Prosedur Kriteria (NSPK).',
                ],
                'HK.00.04' => [
                    'nama' => 'MOU (Kebijakan Hukum)',
                    'keterangan' => 'Naskah terkait perumusan MOU bidang hukum.',
                ],
                'HK.01.07' => [
                    'nama' => 'Instruksi / Surat Edaran Kepala SKPD',
                    'keterangan' => 'Instruksi atau Surat Edaran pimpinan unit/perangkat daerah.',
                ],
                'HK.01.09' => [
                    'nama' => 'Surat Perintah',
                    'keterangan' => 'Naskah terkait Surat Perintah pejabat berwenang.',
                ],
                'HK.01.10' => [
                    'nama' => 'Standar / Pedoman / Prosedur Kerja / Juklak / Juknis',
                    'keterangan' => 'SOP, pedoman kerja, petunjuk pelaksanaan, dan petunjuk teknis instansional.',
                ],
                'HK.01.11' => [
                    'nama' => 'Nota Kesepahaman / MoU / Kontrak / Perjanjian',
                    'keterangan' => 'Naskah Nota Kesepahaman (MoU), kontrak, dan perjanjian kerjasama.',
                ],
                'HK.01.13' => [
                    'nama' => 'Sosialisasi / Penyuluhan / Pembinaan Hukum',
                    'keterangan' => 'Kegiatan sosialisasi, penyuluhan, dan pembinaan hukum.',
                ],
                'HK.02.00' => [
                    'nama' => 'Bantuan Hukum / Konsultasi Hukum / Advokasi',
                    'keterangan' => 'Pemberian bantuan hukum, konsultasi hukum, dan advokasi.',
                ],
            ],
        ],
    ],
];

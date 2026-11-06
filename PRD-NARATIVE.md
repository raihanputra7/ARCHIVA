# Product Requirements Document (PRD)
# ARCHIVA — Portal Sistem Administrasi Persuratan Terintegrasi Web Profil SDN Jatipulo 07

*Dokumen ini merupakan PRD komprehensif ARCHIVA, disusun dengan merujuk pada proposal PKM final "Perancangan Sistem Informasi Administrasi Persuratan Terintegrasi Web Profil pada SDN Jatipulo 07 Jakarta" (Tim PKM UNPAM, 2026) serta hasil diskusi perancangan sebelumnya.*

## 1. Ringkasan Proyek

ARCHIVA adalah portal sistem administrasi persuratan yang menjadi inti dari proyek Pengabdian kepada Masyarakat (PKM) tim mahasiswa Teknik Informatika Universitas Pamulang bersama SDN Jatipulo 07 Jakarta. Proyek ini bertujuan menghadirkan dua hal sekaligus bagi sekolah: web profil sebagai identitas digital resmi, dan sistem administrasi persuratan yang mendigitalkan seluruh proses surat-menyurat internal maupun eksternal. Dokumen ini berfokus pada ARCHIVA sebagai inti sistem administrasi persuratan, mencakup kebutuhan produk secara menyeluruh sebagai acuan pengembangan.

## 2. Latar Belakang

SDN Jatipulo 07 Jakarta baru saja menyelesaikan renovasi dan kini memiliki gedung empat lantai dengan fasilitas yang jauh lebih baik dari sebelumnya. Sekolah juga telah memiliki konektivitas Wi-Fi, meski pemanfaatan infrastruktur digitalnya saat ini masih terbatas untuk kebutuhan operasional dasar seperti CCTV dan akses jaringan internal, belum dioptimalkan untuk menopang sistem administrasi perkantoran digital. Kondisi fisik yang baru ini menjadi momentum untuk sekaligus memperkuat identitas digital sekolah dan memodernisasi tata kelola administrasinya.

Pada sisi administrasi, proses persuratan sekolah melibatkan Tata Usaha, Guru, dan Kepala Sekolah, dengan surat eksternal yang kerap diterima terlebih dahulu melalui grup WhatsApp sebelum didokumentasikan secara manual. Tanpa pengelolaan digital yang terintegrasi, pencatatan dan pencarian dokumen berisiko menjadi tidak efisien, dan proses distribusi maupun tindak lanjut surat sulit dipantau oleh pimpinan. ARCHIVA diusulkan sebagai jawaban atas kebutuhan ini: portal administrasi persuratan yang terintegrasi dengan web profil sekolah, dengan domain dan layanan hosting yang disediakan secara mandiri oleh tim pengembang.

## 3. Rumusan Masalah

Secara formal, proyek ini menjawab empat pertanyaan utama: bagaimana menyediakan identitas digital sekolah yang informatif dan terstruktur; bagaimana mendigitalisasi proses administrasi persuratan agar pencatatan, distribusi, disposisi, persetujuan, dan pengarsipan dokumen dapat dilakukan secara terintegrasi; bagaimana menyediakan tracking dan monitoring status surat bagi Kepala Sekolah, Tata Usaha, dan Guru sesuai hak akses masing-masing; serta bagaimana merancang sistem yang sederhana dan mudah digunakan oleh pengguna sekolah, namun tetap mendukung pengelolaan dokumen dan administrasi secara terstruktur.

Permasalahan konkret yang mendasari rumusan tersebut terbagi ke dalam dua aspek. Pertama, sekolah belum memiliki media informasi terpadu: tidak ada platform digital resmi untuk mempublikasikan profil lembaga, visi dan misi, struktur organisasi, sarana prasarana, program akademik, maupun pengumuman kepada masyarakat. Kedua, manajemen persuratan masih terfragmentasi dan berisiko: jalur surat eksternal tersebar di kanal informal seperti grup WhatsApp sehingga pencatatan dan pelacakan tindak lanjut rentan terlewat, alur surat keluar mulai dari draf hingga penomoran masih berjalan terpisah, dan arsip tersimpan di komputer individu sehingga sulit ditelusuri kembali oleh pihak berwenang dari perangkat lain.

## 4. Tujuan (Goals)

ARCHIVA bertujuan mempercepat distribusi surat dari yang sebelumnya dapat memakan waktu berhari-hari menjadi hitungan menit melalui distribusi elektronik, menghilangkan risiko duplikasi dan kesalahan penomoran lewat penomoran otomatis yang konsisten, serta memberi kepala sekolah visibilitas penuh atas seluruh proses persuratan melalui pelacakan status dan disposisi secara real-time. Di sisi pengarsipan, ARCHIVA bertujuan menyediakan satu sumber arsip digital yang terpusat, terstruktur, dan mudah dicari untuk menggantikan arsip fisik maupun dokumen yang tersebar di komputer individu. Terakhir, dengan alur persetujuan dan disposisi yang sepenuhnya elektronik, ARCHIVA bertujuan meminimalkan kebutuhan mencetak dan mengedarkan dokumen fisik berulang kali dalam administrasi persuratan sehari-hari di SDN Jatipulo 07.

## 5. Ruang Lingkup dan Tahapan Prioritas

Secara menyeluruh, ARCHIVA mencakup modul surat masuk dan disposisi, surat keluar dengan alur persetujuan berjenjang dan penomoran otomatis, arsip digital dengan pencarian terstruktur, dashboard monitoring, manajemen pengguna dan hak akses, serta laporan periodik. Dokumen ini mencakup kebutuhan produk secara menyeluruh sebagai acuan jangka panjang.

Untuk tahap pengerjaan saat ini, prioritas dibatasi secara ketat pada satu hal: membangun fondasi login per-role sesuai lima peran yang telah ditetapkan dalam proposal final. Modul-modul lain seperti surat masuk, disposisi, arsip dan pencarian, tracking status, hingga surat keluar berjenjang dan laporan, didokumentasikan di sini sebagai bagian dari visi utuh ARCHIVA, namun belum masuk cakupan pengerjaan tahap ini dan menyusul pada tahap-tahap berikutnya.

## 6. Pengguna dan Hak Akses

| Peran | Tanggung Jawab | Aktivitas di ARCHIVA |
|---|---|---|
| Admin | Mengelola pengguna dan hak akses | Kelola akun, atur peran dan hak akses seluruh pengguna |
| Kepala Sekolah | Mengawasi dan mengambil keputusan | Menerima surat, disposisi, verifikasi/koreksi, persetujuan dan penandatanganan surat keluar, monitoring status, melihat laporan |
| Tata Usaha (Dapodik dan Keuangan) | Mengelola administrasi persuratan dan arsip | Registrasi surat masuk, upload dokumen, distribusi, menyusun draf surat keluar, arsip, pencarian, laporan; lingkup sesuai unit |
| Guru | Menerima dan menindaklanjuti surat | Membaca surat yang ditujukan, memberi tindak lanjut, mengajukan draf surat keluar |
| Tenaga pendidik lain (security, penjaga sekolah/OB) | Menerima informasi yang relevan | Melihat surat atau pengumuman yang ditujukan kepadanya |

## 7. User Story

Sebagai Admin, saya ingin dapat mengatur hak akses setiap pengguna sesuai peran dan jabatannya masing-masing, sehingga setiap pihak hanya dapat mengakses fitur dan dokumen yang relevan dengan tugasnya.

Sebagai Kepala Sekolah selaku pimpinan dan penandatangan, saya ingin dapat menerima surat, memverifikasi dan mengoreksi, menyetujui serta menandatangani surat keluar sesuai alur berjenjang, dan memantau posisi serta status seluruh surat secara real-time, sehingga saya memiliki kendali penuh atas administrasi persuratan sekolah tanpa harus menelusuri arsip fisik satu per satu.

Sebagai petugas Tata Usaha yang menerima surat masuk, saya ingin dapat langsung menginput data surat dan mengunggah hasil pindai dokumen ke dalam sistem, serta menyusun draf surat keluar, sehingga seluruh naskah dinas segera terdokumentasi dan dapat didistribusikan tanpa menunggu proses fisik berpindah tangan.

Sebagai Guru yang menjadi tujuan sebuah surat, saya ingin menerima notifikasi dan membaca surat tersebut melalui akun saya, memberikan tindak lanjut secara elektronik, serta dapat mengajukan draf surat keluar bila diperlukan, sehingga saya tidak perlu menunggu surat fisik sampai ke tangan saya.

Sebagai tenaga pendidik lain seperti security atau penjaga sekolah, saya ingin menerima surat atau pengumuman yang ditujukan khusus kepada saya melalui akun saya sendiri, sehingga saya tidak perlu bergantung pada penyampaian informasi secara lisan atau manual.

## 8. Gambaran Solusi per Modul

Modul surat masuk menangani pencatatan surat baik yang berasal dari eksternal maupun internal sekolah: petugas Tata Usaha mencatat dan mengarsipkan dokumen secara digital begitu surat diterima, lalu mendistribusikannya secara elektronik kepada penerima yang dituju. Modul disposisi memungkinkan penerima membaca surat dan memberikan tindak lanjut langsung melalui akunnya, dengan status yang dapat dilacak secara real-time oleh pimpinan.

Modul surat keluar menangani alur mulai dari pembuatan konsep oleh staf, verifikasi dan koreksi elektronik oleh pimpinan, persetujuan atau penandatanganan sesuai hierarki jabatan, penomoran surat otomatis dengan format fleksibel, hingga pengiriman dan pengarsipan digital — termasuk opsi mengunduh berkas untuk dikirim manual apabila surat ditujukan ke pihak eksternal.

Modul arsip digital menyediakan penyimpanan dokumen yang terpusat, terkategorisasi, dan dapat dicari dengan cepat, menggantikan arsip fisik maupun dokumen yang tersebar di komputer individu. Modul dashboard monitoring memberi kepala sekolah gambaran menyeluruh atas status dan tindak lanjut setiap surat. Modul manajemen pengguna mengatur hak akses setiap peran sesuai tabel pada bagian 6, dan modul laporan menghasilkan rekap periodik untuk evaluasi kinerja persuratan sekolah.

## 9. Asumsi dan Batasan

Tanda tangan digital pada surat keluar bersifat opsional, disesuaikan dengan kesiapan sekolah. Data kuantitatif seperti jumlah pengguna, volume surat dalam periode tertentu, dan volume arsip akan divalidasi bersama mitra sebelum pelaksanaan penuh, dan dapat memengaruhi konfigurasi sistem di kemudian hari. Pengenalan dan pendampingan penggunaan sistem akan dilakukan kepada pengguna mitra sesuai peran masing-masing setelah modul terkait siap digunakan.

## 10. Luaran yang Diharapkan

Luaran utama proyek ini adalah sistem administrasi persuratan yang terintegrasi dengan web profil SDN Jatipulo 07 Jakarta, yang dirancang berdasarkan kebutuhan dan alur kerja nyata di lingkungan sekolah. Luaran berikutnya berupa web profil sekolah yang memuat profil dan sejarah, visi dan misi, struktur organisasi, informasi tenaga pendidik dan kependidikan, kesiswaan, akademik, sarana dan prasarana, serta berita dan dokumentasi kegiatan. Pada sisi administrasi, luaran berupa modul digitalisasi persuratan sebagaimana dijabarkan pada bagian 8, serta dokumentasi dan panduan penggunaan sistem bagi Kepala Sekolah, Tata Usaha, dan Guru sebagai pengguna.
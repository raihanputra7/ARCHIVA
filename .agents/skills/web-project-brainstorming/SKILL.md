---
name: web-project-brainstorming
description: "Kerangka brainstorming bertahap untuk fitur atau halaman web baru di ARCHIVA. Dipakai hanya jika pemilik project secara eksplisit meminta brainstorming. Stack sudah tetap (Laravel, MySQL, Blade, Tailwind)."
---

# Web Project Brainstorming

## Penyesuaian untuk ARCHIVA

* **Hanya dipakai bila pemilik project meminta brainstorming secara eksplisit** (mis. "brainstorm fitur disposisi"). Jangan aktif otomatis di awal pekerjaan.
* **Stack sudah final dan tidak dibahas ulang**: Laravel, MySQL, Blade, Tailwind (Breeze), Alpine.js, deploy ke shared hosting. Jangan menyarankan Next.js, React, Prisma, Supabase, atau layanan serupa. Detailnya ada di `archiva-laravel-stack`.
* **Lewati pertanyaan yang sudah terjawab** oleh `PRD-NARATIVE.md` (pengguna, peran, modul) dan `DESIGN_SYSTEM.md` (warna, tipografi, komponen).
* **Jangan membuat dokumen blueprint, roadmap, atau rencana pengembangan** kecuali pemilik project memintanya. Hasil brainstorming disampaikan di chat. Jika diminta dokumen, buat singkat dan simpan sebagai `.md`.
* Bahasa Indonesia.

## Gambaran

Kerangka terstruktur untuk membahas sebuah fitur atau halaman web di awal pengerjaannya: ruang lingkup, alur pengguna, tampilan, teknis, aksesibilitas dan performa, serta batas MVP.

## Cara kerja

Jalankan berurutan, satu fase per giliran, dengan maksimal 3 pertanyaan per fase. Jangan menumpahkan semua fase sekaligus. Jika pemilik project belum yakin, ajukan usulan bawaan yang masuk akal dan minta konfirmasi.

### Fase 1: Konsep dan ruang lingkup
* Siapa penggunanya (role mana di PRD) dan masalah apa yang diselesaikan?
* Apa 3 sampai 5 kemampuan wajib?
* Apa yang sengaja tidak dikerjakan (non-goals) agar tidak melebar?

### Fase 2: Pengalaman pengguna dan arsitektur informasi
* Halaman dan rute apa saja yang dibutuhkan?
* Bagaimana alur langkah demi langkah untuk tujuan utama, termasuk keadaan kosong, error, dan tanpa izin?
* Mobile-first atau desktop-first? (Pengguna sering membuka dari HP.)

### Fase 3: Tampilan
* Ikuti `DESIGN_SYSTEM.md`. Bahas hanya hal yang belum tercakup: komponen baru, keadaan interaktif khusus, dan tata letak halaman itu.
* Aturan logo tetap berlaku.

### Fase 4: Teknis (dalam batas stack tetap)
* Model dan tabel apa yang diperlukan, relasinya, dan siapa yang boleh mengakses apa (Policy).
* Apakah ada file upload? Simpan di disk privat dan unduh lewat controller.
* Apakah butuh queue atau jadwal? Hanya lewat cron, tanpa proses persisten.
* Catat risiko keamanan sejak awal: validasi input, otorisasi, dan perlindungan file.

### Fase 5: Aksesibilitas dan performa
* Elemen HTML semantik, satu `<h1>` per halaman, label form, navigasi keyboard, kontras warna.
* Pagination untuk daftar panjang, hindari N+1 query, gambar diberi ukuran dan dioptimalkan.
* SEO hanya relevan untuk web profil publik, bukan portal login.

### Fase 6: Batas MVP
* Apa yang paling minimal agar fitur bisa dipakai di sekolah?
* Apa yang ditunda ke tahap berikutnya?

## Praktik baik

* Ajukan pertanyaan bertahap, jangan semua fase dalam satu balasan.
* Catat non-goals secara eksplisit untuk mencegah scope creep.
* Rencanakan HTML semantik dan responsif dari awal.
* Jangan menyimpan token API, password, atau kredensial di dokumen apa pun.

## Jebakan umum

* **Masalah:** cakupan melebar sebelum MVP selesai. **Solusi:** tegakkan Fase 6, pindahkan hal yang bagus-kalau-ada ke tahap berikutnya.
* **Masalah:** desain mobile baru dipikirkan di akhir. **Solusi:** bahas pola responsif di Fase 2 sebelum memilih tata letak.
* **Masalah:** mengulang pertanyaan yang sudah dijawab PRD. **Solusi:** baca PRD dulu, tanya hanya yang masih kosong.

## Batasan

* Skill ini hanya memetakan konsep, alur, dan fitur. Tidak menulis kode implementasi.
* Hasil brainstorming adalah rancangan yang fleksibel dan diperbarui saat kendala teknis ditemukan.
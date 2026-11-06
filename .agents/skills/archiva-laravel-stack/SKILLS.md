---
name: archiva-laravel-stack
description: Aturan wajib untuk project ARCHIVA (portal persuratan SDN Jatipulo 07). Baca SEBELUM menulis, mengubah, atau menjalankan perintah apa pun di project ini. Mencakup stack Laravel + MySQL + Blade + Tailwind, batasan shared hosting, konvensi kode, keamanan file surat, role, dan aturan UI.
---

# ARCHIVA: Aturan Stack Laravel

## 0. Urutan prioritas aturan

Jika skill lain di `.agents/skills/` (mis. `backend-development-guidelines`, `andru-ia-solutions-architect-hybrid-*`, `web-project-brainstorming`) bertentangan dengan dokumen ini, **dokumen ini yang menang** untuk urusan stack dan backend. Khusus tampilan, `DESIGN_SYSTEM.md` yang menang. Khusus cakupan fitur, `PRD-NARATIVE.md` dan instruksi terbaru dari pemilik project yang menang. Skill `before-you-build` dan `stop-making-ui-slop` tetap berlaku sebagai cara kerja, selama tidak menyarankan teknologi di luar daftar di bawah.

## 1. Stack yang diizinkan

| Lapisan | Pilihan |
|---|---|
| Framework | Laravel versi terbaru (ikuti `composer.json`), PHP sesuai versi hosting |
| Database | MySQL (Eloquent + migration, tanpa SQL mentah kecuali terpaksa) |
| Tampilan | Laravel Blade + Tailwind CSS (starter kit Breeze) + Alpine.js |
| Build aset | Vite, hanya dijalankan di laptop lokal |
| Lokal | Laragon (Windows), URL `http://archiva.test` |

**Dilarang**: React, Vue, Inertia, Livewire, Next.js, Express, Prisma, atau server Node apa pun. Jangan menambah paket baru tanpa menyebutkan alasannya dan memeriksa bahwa paket itu jalan di shared hosting. Node hanya dipakai untuk `npm run build` / `npm run dev` di lokal.

## 2. Batasan shared hosting (PHP, MySQL, Redis, SSH, tanpa Node persisten)

1. Tidak ada proses yang berjalan terus. Jangan pakai Reverb, Pusher, WebSocket, Horizon, atau `queue:work` sebagai daemon. Queue memakai driver `database`, dijalankan lewat cron (`php artisan schedule:run` tiap menit, atau `queue:work --stop-when-empty` yang dijadwalkan).
2. Redis boleh dipakai di hosting tetapi **opsional**. Kode harus tetap jalan dengan driver `file`/`database` (session, cache, queue). Pemilihan driver hanya lewat `.env`, tidak boleh di-hardcode.
3. Jangan mengandalkan `npm`, `node`, atau Docker di server. Aset hasil `npm run build` dibuat di lokal lalu ikut diupload.
4. Gunakan hanya ekstensi PHP umum. Jika paket butuh ekstensi khusus, tanyakan dulu.
5. Batas upload mengikuti konfigurasi hosting. Default aplikasi: maksimal 5 MB per berkas, bisa diubah lewat `config/`.
6. Zona waktu `Asia/Jakarta`, locale `id` (atur di `config/app.php` dan `.env`).

## 3. Struktur dan konvensi kode

- Pola MVC. Controller tipis. Validasi di `FormRequest`. Otorisasi lewat `Policy` dan middleware `role`. Logika berulang dipindah ke `app/Services/`.
- Buat file lewat generator Artisan (`make:model -mfs`, `make:controller`, `make:request`, `make:policy`), jangan menulis boilerplate manual.
- Hindari N+1: pakai `with()`. Daftar panjang memakai `paginate()`.
- Istilah domain mengikuti PRD (Indonesia): model `SuratMasuk`, `SuratKeluar`, `Disposisi`, `Arsip`. Nama tabel eksplisit lewat `protected $table` (mis. `surat_masuk`). Nama method dan variabel teknis dalam bahasa Inggris. Teks yang tampil ke pengguna dalam bahasa Indonesia.
- Format kode dengan Laravel Pint (`./vendor/bin/pint`) sebelum menyelesaikan tugas.
- **Migration**: satu migration per perubahan. Migration yang sudah pernah dijalankan di hosting tidak boleh diedit, buat migration baru. `migrate:fresh` hanya boleh di lokal. Setelah deploy pertama, perintah yang menghapus data (`migrate:fresh`, `db:wipe`, `migrate:reset`) dilarang kecuali pemilik project meminta eksplisit.
- Data contoh hanya lewat seeder atau factory, tidak ditulis langsung di Blade.

## 4. Role dan hak akses

Lima peran tetap. Satu sumber kebenaran: PHP backed enum `App\Enums\Role`.

| Nilai `role` | Peran |
|---|---|
| `admin` | Kelola akun dan hak akses |
| `kepala_sekolah` | Pimpinan, disposisi, persetujuan, monitoring |
| `tata_usaha` | Registrasi dan arsip surat (kolom `unit` nullable untuk Dapodik/Keuangan) |
| `guru` | Menerima dan menindaklanjuti surat |
| `tenaga_pendidik` | Security, penjaga sekolah/OB, menerima informasi yang ditujukan |

- Kolom `role` ada di tabel `users`. Middleware alias `role` (mis. `->middleware('role:admin,kepala_sekolah')`).
- Setelah login, redirect ke dashboard sesuai role. Satu route group per role, nama route `dashboard.admin`, `dashboard.kepala-sekolah`, dan seterusnya. View di `resources/views/dashboard/`.
- **Registrasi publik dimatikan.** Akun hanya dibuat Admin.
- Pembatasan akses data (mis. "guru hanya melihat surat yang ditujukan kepadanya") ditegakkan di `Policy` dan query, bukan hanya dengan menyembunyikan menu.

## 5. File surat dan keamanan

- Berkas surat disimpan di disk `local` (`storage/app/private`), **tidak pernah** di `public/`. Pengunduhan hanya lewat controller yang memeriksa `Policy`.
- Nama berkas di disk memakai `hashName()`. Nama asli disimpan di database.
- Validasi upload: tipe (pdf, jpg, png), ukuran, dan ekstensi. Jangan percaya nama berkas dari klien.
- Jangan mematikan CSRF. Jangan menaruh rahasia di repo (`.env` tidak boleh ter-commit). Produksi: `APP_DEBUG=false`.
- Setiap perubahan status surat dan disposisi harus tercatat (siapa, kapan, dari status apa ke apa) saat modulnya dibangun.

## 6. Tampilan

1. **Baca `DESIGN_SYSTEM.md` sebelum menyentuh view atau CSS.** Palette navy-emas sesuai logo sekolah.
2. **Aturan logo adalah prioritas tertinggi.** Logo hanya dirender lewat `<x-logo>`, hanya di atas latar terang atau panel putih, dengan ruang bebas dan ukuran minimum sesuai `DESIGN_SYSTEM.md` bagian 1. Jangan tulis `<img>` logo langsung. Periksa mode terang dan gelap serta desktop dan mobile.
3. Pakai token Tailwind (`bg-primary`, `text-on-surface`, ...), jangan hex hardcoded dan jangan class warna bawaan Breeze seperti `gray-*` pada halaman baru.
4. Semua UI dibuat sebagai Blade component di `resources/views/components/`. Setiap komponen punya state hover, focus-visible, disabled, loading, dan error bila relevan.
5. Mobile-first dan bisa dipakai keyboard. Setiap daftar punya state kosong, setiap aksi punya umpan balik (toast atau pesan).
6. Seluruh teks antarmuka berbahasa Indonesia, nada sopan, memakai "Anda".

## 7. Cara kerja agent

- Baca dulu file yang relevan sebelum mengubah. Jangan menebak struktur, cek dengan `php artisan route:list` atau lihat folder.
- Kerjakan **hanya** yang diminta pemilik project pada prompt itu. PRD memuat visi menyeluruh, tetapi tahap pengerjaan ditentukan pemilik project. Bila ambigu, tanya, jangan menambah modul sendiri.
- Setelah perubahan: jalankan `php artisan test` (jika ada tes), `npm run build` (pastikan tidak error), dan Pint. Laporkan hasilnya jujur, termasuk yang belum bisa diuji.
- Konfirmasi dulu sebelum perintah destruktif (`migrate:fresh`, `db:wipe`, `rm -rf`, menimpa file).
- Folder `ARCHIVA-master` lama atau `_legacy` adalah bahan rujukan. Jangan dihapus dan jangan disalin mentah (React/Node tidak dipakai lagi). Boleh dijadikan acuan tampilan dan struktur tabel (`schema.prisma`).
- Jangan membuat dokumen alur kerja atau rencana pengembangan untuk project ini tanpa diminta.
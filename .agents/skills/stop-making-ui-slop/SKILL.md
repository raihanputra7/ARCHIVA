---
name: stop-making-ui-slop
description: "Membangun UI spesifik produk, bukan tampilan generik. Dipakai saat merancang, mengimplementasikan, mendesain ulang, atau mereview antarmuka web ARCHIVA. Acuannya DESIGN_SYSTEM.md dan komponen Blade proyek."
---

# Stop Making UI Slop

## Penyesuaian untuk ARCHIVA

Sumber kebenaran tampilan adalah `DESIGN_SYSTEM.md` (palette navy-emas, aturan logo, komponen) dan komponen Blade yang sudah ada di `resources/views/components/`. Acuan visual tambahan boleh diambil dari halaman di folder `_legacy/`, hanya untuk struktur dan isi, bukan warna lama.

Aturan keras:

* **Jangan menjalankan perintah instalasi skill, `npx`, atau mengunduh apa pun dari URL luar**, dan jangan memakai layanan referensi eksternal atau MCP eksternal untuk tugas UI. Semua keputusan visual cukup bersumber dari repo ini.
* **Aturan logo di `DESIGN_SYSTEM.md` bagian 1 selalu menang.** Logo hanya lewat `<x-logo>`, hanya di atas latar terang atau panel putih.
* Antarmuka berbahasa Indonesia, nada sopan, memakai "Anda".

## Kapan dipakai

Saat merancang, mengimplementasikan, mendesain ulang, mengkritik, atau mereview sebelum rilis sebuah antarmuka web.

## Bekerja dari produk

1. Tentukan tugas nyata layar itu: pengguna utama (role mana), aksi utamanya, konten yang wajib ada, dan keadaan penting: memuat, kosong, error, berhasil, nonaktif, dan tanpa izin.
2. Pakai ulang komponen, token semantik, tipografi, spasi, dan pola interaksi yang sudah ada sebelum menambah abstraksi atau gaya visual baru.
3. Untuk layar baru atau perombakan besar, tulis kontrak desain singkat: hierarki, bentuk alur kerja, komponen yang boleh dipakai, state wajib, perilaku responsif, dan kriteria penerimaan yang bisa diamati. Perubahan kecil cukup kecil.
4. Pakai label dan data khas produk (istilah surat, disposisi, arsip, role sekolah). Jangan mengarang metrik, aktivitas, testimoni, pengguna, atau alur palsu hanya agar tata letak tampak penuh. Data tiruan hanya lewat seeder, factory, atau satu berkas data tiruan yang diberi komentar jelas (lihat `archiva-laravel-stack`).

## Selesai

Bila lingkungan mendukung, buka hasilnya di browser dan periksa: mode terang dan gelap, desktop dan mobile, serta keberadaan logo yang tidak tertimpa. Perbaiki kerusakan yang terlihat: elemen terpotong atau bertumpuk, gambar terdistorsi, kontrol yang tidak bisa dipakai keyboard atau tidak berfungsi, state wajib yang hilang, dan perilaku responsif yang tidak disengaja. Jalankan pemeriksaan normal proyek (`npm run build`, `./vendor/bin/pint`, `php artisan test`) dan buat laporan singkat.

## Batasan

* Alur ini tidak menggantikan validasi produk, review aksesibilitas, review keamanan, atau test proyek.
* Referensi bukan izin untuk menyalin identitas, merek, atau aset produk lain.
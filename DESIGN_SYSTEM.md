---
name: Archiva Navy & Gold
description: Design system ARCHIVA (portal persuratan SDN Jatipulo 07). Palette diturunkan dari logo sekolah. Wajib dibaca sebelum menyentuh view, komponen, atau CSS apa pun.
colors:
  # --- Brand (dari logo sekolah) ---
  navy: '#1B3A6B'
  navy-dark: '#0F2347'
  gold: '#C9A84C'
  gold-light: '#E8C97A'
  gold-text: '#8A6D1F'            # emas khusus teks di atas putih (kontras sekitar 4.9:1). Jangan pakai #C9A84C untuk teks.
  # --- Semantik (mode terang) ---
  primary: '#1B3A6B'
  on-primary: '#FFFFFF'
  primary-strong: '#0F2347'       # hover/pressed tombol primary
  primary-container: '#E3EAF6'
  on-primary-container: '#0F2347'
  secondary: '#C9A84C'
  on-secondary: '#0F2347'         # teks di atas emas selalu navy-dark (kontras sekitar 6.8:1)
  secondary-container: '#FBF1D3'
  on-secondary-container: '#5C4710'
  surface: '#F8F9FB'
  surface-lowest: '#FFFFFF'       # kartu, topbar, sidebar, panel logo
  surface-container-low: '#F1F4F9'
  surface-container: '#E8EDF5'
  surface-container-high: '#DDE4F0'
  on-surface: '#0F1B2D'
  on-surface-variant: '#4A5163'
  outline: '#757B8A'
  outline-variant: '#C8CDD8'
  border-subtle: '#E2E8F0'
  inverse-surface: '#1D2A40'
  inverse-on-surface: '#EAF0FA'
  # --- Status ---
  error: '#BA1A1A'
  on-error: '#FFFFFF'
  error-container: '#FFDAD6'
  on-error-container: '#93000A'
  success: '#1E7B4F'
  success-container: '#DDF3E7'
  on-success-container: '#0F4D2F'
  warning: '#8A6D1F'
  warning-container: '#FBF1D3'
  on-warning-container: '#5C4710'
colors-dark:
  surface: '#0B1730'
  surface-lowest: '#101F3D'
  surface-container-low: '#13244A'
  surface-container: '#182B54'
  surface-container-high: '#1F355F'
  on-surface: '#E6ECF7'
  on-surface-variant: '#AAB4C8'
  outline: '#7D87A0'
  outline-variant: '#2C3E63'
  border-subtle: '#22345A'
  primary: '#A9C0EC'
  on-primary: '#0F2347'
  primary-container: '#1B3A6B'
  on-primary-container: '#D6E2FA'
  secondary: '#E8C97A'
  on-secondary: '#0F2347'
  error: '#FFB4AB'
  success: '#7FD6A5'
  warning: '#E8C97A'
typography:
  display-hero:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '800'
    lineHeight: 56px
    letterSpacing: -0.025em
  display-hero-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '800'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 26px
    fontWeight: '700'
    lineHeight: 34px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.015em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
    letterSpacing: 0em
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 24px
    letterSpacing: 0em
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: 0.005em
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 14px
    letterSpacing: 0.04em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
elevation:
  sm: '0 1px 2px rgba(15, 35, 71, 0.06)'
  md: '0 4px 12px rgba(15, 35, 71, 0.08)'
  lg: '0 12px 32px rgba(15, 35, 71, 0.12)'
motion:
  fast: 150ms
  base: 200ms
  easing: 'cubic-bezier(0.2, 0, 0, 1)'
---

# ARCHIVA Design System: Navy & Gold

Antarmuka ARCHIVA dipakai oleh Kepala Sekolah, Tata Usaha, Guru, dan tenaga pendidik lain di SDN Jatipulo 07. Banyak di antaranya bukan pengguna teknis, jadi tampilannya harus tenang, resmi, mudah dibaca, dan jelas mana yang harus diklik. Rasanya seperti dokumen dinas yang rapi, bukan dashboard startup. Navy memberi kesan resmi dan tepercaya, emas dipakai hemat sebagai penanda perhatian.

## 1. Aturan Logo (prioritas tertinggi)

Logo sekolah tidak boleh tertabrak, tertutup, atau kehilangan keterbacaan di bagian mana pun. Aturan ini mengalahkan aturan estetika lain di dokumen ini.

1. **Latar logo selalu terang.** Logo hanya boleh berada di atas `surface-lowest` (#FFFFFF) atau `surface` (#F8F9FB). Logo tidak boleh ditaruh langsung di atas navy, emas, gradien, foto, glass/blur, atau warna status.
2. **Jika area sekitarnya gelap, pakai panel putih.** Ini berlaku di mode gelap, panel kiri halaman login (navy + foto gedung), footer navy, dan modal bertema gelap. Logo diletakkan di dalam panel putih `rounded-xl` dengan padding `p-2`. Logo tidak boleh di-invert, di-filter, diberi opacity, atau diwarnai ulang.
3. **Ruang bebas minimal 0,5 kali tinggi logo** di keempat sisi. Tidak ada teks, tombol, badge, atau ikon di dalam zona ini.
4. **Ukuran minimum.** Tinggi minimal 40px di topbar dan sidebar, 32px mutlak terkecil (mis. kartu kecil). Pertahankan aspect ratio (`object-contain`, `h-auto w-auto`), jangan di-stretch atau di-crop.
5. **Tidak boleh ada yang menimpa logo.** Dropdown, sticky header, tombol melayang, toast, atau elemen dekoratif tidak boleh menutupi logo. Tinggi topbar tetap (64px) supaya logo tidak terpotong saat scroll.
6. **Satu pintu masuk.** Logo hanya dirender lewat komponen `<x-logo>` (`resources/views/components/logo.blade.php`). Komponen ini yang menegakkan aturan 1 sampai 5 (otomatis memakai panel putih bila `variant="on-dark"`). Dilarang menulis `<img src=".../logo...">` langsung di view.
7. Sebelum menyerahkan halaman apa pun yang memuat logo, cek di mode terang dan gelap, desktop dan mobile.

## 2. Warna

**Proporsi**: sekitar 70% permukaan netral (putih dan abu kebiruan), 25% navy (tombol utama, judul, item aktif), 5% emas (aksen). Emas bukan warna dominan.

| Token | Pemakaian | Catatan |
|---|---|---|
| `surface` | Latar halaman | Bukan putih murni, agar kartu putih terlihat terangkat |
| `surface-lowest` | Kartu, topbar, sidebar, panel logo | Selalu putih (mode terang) |
| `primary` (navy) | Tombol utama, link, item menu aktif, judul penting | Teks di atasnya `on-primary` |
| `navy-dark` | Panel hero/login, kartu statistik sorotan, hover tombol primary | Tidak untuk latar logo |
| `secondary` (emas) | Garis aksen 3px di item aktif, ikon sorotan, satu tombol aksen per layar, indikator penting | Teks di atasnya wajib `on-secondary` (navy-dark) |
| `gold-text` | Teks atau ikon emas di atas putih | **Jangan** pakai `#C9A84C` sebagai teks di atas putih, kontrasnya hanya sekitar 2,3:1 |
| `error`/`success`/`warning` | Status dan validasi | Selalu disertai ikon atau teks, jangan warna saja |

Aturan kontras: teks biasa minimal 4,5:1, teks besar (18px ke atas atau 14px tebal) dan ikon fungsional minimal 3:1. Jangan hardcode hex di view, selalu pakai token (`bg-primary`, `text-on-surface`).

## 3. Tipografi

Plus Jakarta Sans, dipasang lokal lewat `@fontsource-variable/plus-jakarta-sans` supaya ikut ter-build dan tidak bergantung CDN. Skala mengikuti blok `typography` di atas. Pemakaian: `display-hero` hanya untuk halaman login atau landing, `headline-lg` judul halaman, `headline-sm` judul kartu/seksi, `body-md` teks utama (15px), `body-sm` keterangan, `label-*` tombol, badge, header tabel. Angka di tabel (nomor surat, tanggal) memakai `tabular-nums`.

## 4. Spacing, Radius, Elevasi

Spacing mengikuti skala `spacing` (kelipatan 4px). Radius: input dan tombol `DEFAULT` (0.5rem), kartu `lg` (1rem), modal `xl` (1.5rem), badge dan avatar `full`. Elevasi: kartu diam memakai `sm`, dropdown dan popover `md`, modal `lg`. Bayangan selalu bernuansa navy (lihat nilai `elevation`), bukan hitam murni. Jangan menumpuk bayangan dan border tebal sekaligus.

## 5. Layout

| Area | Aturan |
|---|---|
| Topbar | Tinggi 64px, latar `surface-lowest`, border bawah `border-subtle`, berisi logo (via `<x-logo>`), pencarian, toggle tema, menu akun. Tidak transparan dan tidak blur. |
| Sidebar | Latar `surface-lowest`, lebar 264px (desktop). Item aktif: latar `primary-container`, teks `primary`, garis emas 3px di kiri. Di bawah `lg` berubah menjadi drawer yang dibuka tombol menu. Menu hanya menampilkan fitur sesuai role. |
| Konten | Lebar maksimum 1280px, padding `margin` (2rem desktop, 1rem mobile), jarak antarkartu `gutter`. |
| Halaman login | Dua kolom: kiri panel `navy-dark` dengan foto gedung dan slogan (logo di panel putih, lihat aturan 2), kanan form di atas putih dengan logo utama. Di mobile hanya kolom form. |
| Breakpoint | Bawaan Tailwind: sm 640, md 768, lg 1024, xl 1280. Rancang mobile-first, karena guru dan TU sering membuka dari HP. |

## 6. Komponen

Semua komponen dibuat sebagai Blade component di `resources/views/components/`, bukan disalin antar halaman. Setiap komponen wajib punya state: default, hover, focus-visible, disabled, dan (bila relevan) loading dan error.

| Komponen | Spesifikasi |
|---|---|
| Button | Varian: `primary` (navy, teks putih), `accent` (emas, teks navy-dark, maksimal satu per layar), `secondary` (outline navy), `ghost`, `danger`. Tinggi 36/40/48px (sm/md/lg), target sentuh minimal 44px di mobile. Loading: spinner kecil dan tombol dinonaktifkan. |
| Input / Select / Textarea | Tinggi 44px, radius `DEFAULT`, border `outline-variant`, label selalu di atas dan terhubung (`for`/`id`). Fokus: ring 2px `primary` dengan offset 2px. Error: border `error`, pesan di bawah dengan ikon, dalam Bahasa Indonesia. |
| Card | `surface-lowest`, border `border-subtle`, radius `lg`, shadow `sm`, padding 1.5rem. |
| Tabel surat | Header `surface-container-low` dengan `label-md`, baris hover `surface-container-low`, tanpa zebra. Kolom status memakai Badge. Di mobile berubah menjadi daftar kartu. Wajib ada pencarian, filter, paginasi, dan keadaan kosong. |
| Badge status | Pil `full`, teks `label-sm`, ikon kecil di kiri, warna sesuai bagian 7. |
| Modal | Radius `xl`, shadow `lg`, scrim `navy-dark` 50%, ditutup dengan tombol silang, Esc, dan klik scrim. Fokus terkunci di dalam modal. |
| Toast | Muncul kanan atas (mobile: atas penuh), hilang otomatis 5 detik, bisa ditutup manual, `role="status"` (error memakai `role="alert"`). |
| Empty state | Ikon, judul singkat, satu kalimat penjelasan, satu tombol aksi. Jangan biarkan area kosong tanpa penjelasan. |
| Skeleton / Loading | Skeleton berwarna `surface-container` saat data dimuat, jangan layar kosong. |
| Dropdown / Menu akun | Radius `md`, shadow `md`, item minimal 40px, navigasi keyboard (panah, Enter, Esc). |

## 7. Status Surat

Status harus mudah dibedakan tanpa bergantung warna saja, jadi setiap status punya ikon dan label.

| Status | Arti | Warna badge |
|---|---|---|
| Diterima | Surat tercatat oleh TU | `primary-container` / `on-primary-container` |
| Didisposisikan | Sudah diteruskan Kepala Sekolah | `secondary-container` / `on-secondary-container` |
| Diproses | Sedang ditindaklanjuti penerima | `surface-container-high` / `on-surface` |
| Selesai | Tindak lanjut tuntas | `success-container` / `on-success-container` |
| Terlambat / Ditolak | Lewat batas atau dikembalikan | `error-container` / `on-error-container` |
| Draf | Surat keluar belum diajukan | `surface-container` / `on-surface-variant` |

Daftar ini boleh bertambah saat modul surat keluar dibangun, tetapi pola warnanya harus tetap memakai token di atas.

## 8. Mode Gelap

Mode gelap diaktifkan lewat class `dark` pada `<html>` (disimpan di `localStorage`, diikuti preferensi sistem saat pertama kali, ditangani Alpine.js). Token dibalik lewat CSS variable (blok `colors-dark`), jadi view cukup memakai `bg-surface` dan `text-on-surface`, tanpa `dark:` di tiap elemen. Di mode gelap, `primary` menjadi biru muda (`#A9C0EC`) agar terbaca, emas memakai `gold-light`. Aturan logo nomor 2 tetap berlaku: logo selalu di panel putih.

## 9. Ikon, Bahasa, dan Format Data

Ikon: Heroicons outline (via paket Blade), ukuran 20px, stroke 1.5, warna mengikuti teks. Dilarang memakai emoji sebagai ikon. Seluruh teks antarmuka berbahasa Indonesia, nada sopan dan lugas, memakai "Anda" (mis. "Surat berhasil disimpan", "Anda tidak memiliki akses ke halaman ini"). Tanggal tampil `6 November 2026` (Carbon locale `id`), zona waktu `Asia/Jakarta`, nomor surat memakai `tabular-nums`. Pesan error validasi harus menjelaskan cara memperbaikinya, bukan sekadar "Invalid".

## 10. Aksesibilitas dan Motion

Semua elemen interaktif bisa dijangkau keyboard dengan fokus yang terlihat. Gunakan elemen semantik (`button`, `nav`, `main`, `table`), `aria-label` untuk tombol ikon, `alt` bermakna untuk gambar (logo: "Logo SDN Jatipulo 07"). Transisi 150ms (hover) dan 200ms (modal, drawer) dengan easing dari token `motion`. Hormati `prefers-reduced-motion` dengan mematikan animasi non-esensial. Hindari animasi dekoratif berlebihan, tujuannya membantu orientasi, bukan pamer.

## 11. Yang Harus Dihindari

Gradien ungu atau biru neon, kartu dengan blur kaca, emas sebagai warna teks di atas putih, lebih dari satu tombol aksen per layar, logo di atas warna atau foto, ikon emoji, hex hardcoded di view, data contoh palsu yang ditulis langsung di Blade (pakai seeder), dan halaman tanpa state kosong atau error.

---

## Lampiran A: `resources/css/app.css` (Tailwind v4, bawaan Breeze terbaru)

Pola ini memakai CSS variable sebagai sumber nilai, lalu dipetakan ke utility Tailwind, sehingga mode gelap otomatis tanpa `dark:` di setiap elemen. Pasang font dulu: `npm install @fontsource-variable/plus-jakarta-sans`.

```css
@import 'tailwindcss';
@import '@fontsource-variable/plus-jakarta-sans';

@custom-variant dark (&:where(.dark, .dark *));

/* Nilai mode terang */
:root {
  --surface: #F8F9FB;
  --surface-lowest: #FFFFFF;
  --surface-container-low: #F1F4F9;
  --surface-container: #E8EDF5;
  --surface-container-high: #DDE4F0;
  --on-surface: #0F1B2D;
  --on-surface-variant: #4A5163;
  --outline: #757B8A;
  --outline-variant: #C8CDD8;
  --border-subtle: #E2E8F0;
  --primary: #1B3A6B;
  --on-primary: #FFFFFF;
  --primary-container: #E3EAF6;
  --on-primary-container: #0F2347;
  --secondary: #C9A84C;
  --on-secondary: #0F2347;
  --secondary-container: #FBF1D3;
  --on-secondary-container: #5C4710;
  --error: #BA1A1A;
  --error-container: #FFDAD6;
  --on-error-container: #93000A;
  --success: #1E7B4F;
  --success-container: #DDF3E7;
  --on-success-container: #0F4D2F;
  --warning: #8A6D1F;
}

/* Nilai mode gelap */
.dark {
  --surface: #0B1730;
  --surface-lowest: #101F3D;
  --surface-container-low: #13244A;
  --surface-container: #182B54;
  --surface-container-high: #1F355F;
  --on-surface: #E6ECF7;
  --on-surface-variant: #AAB4C8;
  --outline: #7D87A0;
  --outline-variant: #2C3E63;
  --border-subtle: #22345A;
  --primary: #A9C0EC;
  --on-primary: #0F2347;
  --primary-container: #1B3A6B;
  --on-primary-container: #D6E2FA;
  --secondary: #E8C97A;
  --on-secondary: #0F2347;
  --error: #FFB4AB;
  --success: #7FD6A5;
  --warning: #E8C97A;
}

/* Petakan ke utility Tailwind: bg-surface, text-on-surface, bg-primary, dst. */
@theme inline {
  --font-sans: 'Plus Jakarta Sans Variable', ui-sans-serif, system-ui, sans-serif;

  --color-surface: var(--surface);
  --color-surface-lowest: var(--surface-lowest);
  --color-surface-container-low: var(--surface-container-low);
  --color-surface-container: var(--surface-container);
  --color-surface-container-high: var(--surface-container-high);
  --color-on-surface: var(--on-surface);
  --color-on-surface-variant: var(--on-surface-variant);
  --color-outline: var(--outline);
  --color-outline-variant: var(--outline-variant);
  --color-border-subtle: var(--border-subtle);
  --color-primary: var(--primary);
  --color-on-primary: var(--on-primary);
  --color-primary-container: var(--primary-container);
  --color-on-primary-container: var(--on-primary-container);
  --color-secondary: var(--secondary);
  --color-on-secondary: var(--on-secondary);
  --color-secondary-container: var(--secondary-container);
  --color-on-secondary-container: var(--on-secondary-container);
  --color-error: var(--error);
  --color-error-container: var(--error-container);
  --color-on-error-container: var(--on-error-container);
  --color-success: var(--success);
  --color-success-container: var(--success-container);
  --color-on-success-container: var(--on-success-container);
  --color-warning: var(--warning);
}

/* Warna brand tetap (tidak berubah antar mode) */
@theme {
  --color-navy: #1B3A6B;
  --color-navy-dark: #0F2347;
  --color-gold: #C9A84C;
  --color-gold-light: #E8C97A;
  --color-gold-text: #8A6D1F;

  --shadow-sm: 0 1px 2px rgba(15, 35, 71, 0.06);
  --shadow-md: 0 4px 12px rgba(15, 35, 71, 0.08);
  --shadow-lg: 0 12px 32px rgba(15, 35, 71, 0.12);
}

body {
  background-color: var(--surface);
  color: var(--on-surface);
}
```

Catatan: nama class di Breeze bawaan (mis. `bg-gray-100`, `text-gray-800`) masih dipakai di view auth. Saat memindahkan UI, ganti ke token di atas supaya konsisten.
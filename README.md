# ARCHIVA — Sistem Informasi Persuratan & Disposisi Digital

<p align="center">
  <img src="public/images/logo-sdn-jatipulo07.png" alt="Logo SDN Jatipulo 07" width="96">
</p>

<p align="center">
  <strong>SDN Jatipulo 07 Jakarta Barat</strong><br>
  Platform tata kelola dokumen dinas, registrasi surat masuk/keluar, dan disposisi instruksi berbasis peran.
</p>

---

## 📌 Tentang ARCHIVA

**ARCHIVA** adalah aplikasi web manajemen naskah dinas resmi yang dirancang khusus untuk memenuhi proses bisnis persuratan riil di lingkungan Sekolah Dasar Negeri (SDN Jatipulo 07). ARCHIVA menggantikan pencatatan buku agenda manual dengan sistem arsip digital terpadu, pelacakan status secara real-time, dan alur disposisi instruksi yang akuntabel.

---

## ✨ Fitur Utama

- **📥 Tata Kelola Surat Masuk**
  - Registrasi surat masuk dengan format penomoran agenda otomatis (`ARC/SM/YYYY/XXXX`).
  - Klasifikasi kepentingan sumber surat: Dinas Pendidikan/Pemerintah, Orang Tua/Wali Murid, serta Lembaga/Mitra Swasta.
  - Unggah dan pratinjau berkas digital (PDF/gambar).
  - Pelacakan status terpadu (*Diterima*, *Didisposisikan*, *Diproses*, *Selesai*).

- **📝 Alur Disposisi Multi-Assignee & Riwayat Status**
  - Pembuatan lembar disposisi jamak (*multi-assignee*) kepada Guru, TU, atau Tendik dalam satu kali instruksi.
  - Penentuan tenggat waktu, sifat disposisi (*Biasa*, *Penting*, *Segera*, *Rahasia*), dan catatan instruksi pimpinan.
  - Pelacakan tindak lanjut penerima disposisi dengan audit trail otomatis (*Riwayat Status*).
  - Cetak Lembar Disposisi standar kedinasan yang siap diunduh atau diprint.

- **📤 Manajemen Surat Keluar & Relasi Balasan**
  - Pencatatan dokumen/naskah dinas resmi sekolah untuk pihak eksternal maupun internal.
  - Integrasi dua arah antara Surat Masuk dan Surat Keluar (penerbitan surat balasan resmi langsung merujuk pada surat masuk terkait).

- **👥 Multi-Role & Autentikasi Berjenjang**
  - **Kepala Sekolah**: Disposisi surat, pemantauan seluruh instruksi, dan dashboard analitik eksekutif.
  - **Tata Usaha (TU)**: Pencatatan surat masuk & keluar, penomoran agenda, dan penerbitan disposisi.
  - **Guru**: Monitoring tugas disposisi tindak lanjut dan pengarsipan.
  - **Tenaga Pendidik (Tendik)**: Pemantauan instruksi operasional dan sarpras.
  - **Admin**: Tata kelola pengguna dan konfigurasi sistem.

- **🎨 Desain Antarmuka Eksklusif**
  - Dibangun berdasarkan *Design System* resmi SDN Jatipulo 07 (Navy & Gold).
  - Dukungan **Mode Terang** dan **Mode Gelap (Dark Mode)** yang ramah mata.
  - Sidebar responsif dengan interaksi toggle buka/tutup yang dipicu oleh logo sekolah.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Blade Components, Tailwind CSS v4, Alpine.js, Vite
- **Visualisasi & Analitik**: Chart.js
- **Basis Data**: MySQL (Produksi / Dev), SQLite (Automated Tests)
- **Code Quality**: Laravel Pint, PHPUnit (Feature & Unit Tests)

---

## 🚀 Panduan Instalasi

### 1. Prasyarat Sistem
- PHP >= 8.2 (ekstensi: pdo, mbstring, openssl, tokenizer, xml, ctype, json)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL >= 8.0 / MariaDB >= 10.4

### 2. Kloning & Pemasangan Dependensi
```bash
# Clone repositori
git clone https://github.com/raihanputra7/ARCHIVA.git
cd ARCHIVA

# Install paket PHP
composer install

# Install paket JavaScript
npm install
```

### 3. Konfigurasi Lingkungan (.env)
```bash
# Salin template environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

Sesuaikan konfigurasi database pada berkas `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=archiva
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Database
```bash
# Jalankan migrasi tabel dan data awal akun demo
php artisan migrate --seed

# Hubungkan direktori storage file upload
php artisan storage:link
```

### 5. Kompilasi Aset & Menjalankan Server
```bash
# Kompilasi frontend (mode produksi)
npm run build

# Jalankan server lokal
php artisan serve
```
Akses aplikasi melalui peramban di: `http://localhost:8000`

---

## 🧪 Pengujian Otomatis (Testing)

Aplikasi dilengkapi dengan rangkaian automated test untuk memvalidasi alur disposisi, perizinan role, dan relasi persuratan:

```bash
# Menjalankan seluruh pengujian PHPUnit
php artisan test

# Menjalankan linter code style (Pint)
./vendor/bin/pint --test
```

---

## 📄 Lisensi

Aplikasi dikembangkan untuk kebutuhan operasional kedinasan **SDN Jatipulo 07 Jakarta Barat**.

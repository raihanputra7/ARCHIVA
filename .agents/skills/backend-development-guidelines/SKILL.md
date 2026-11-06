---
name: backend-development-guidelines
description: "Pedoman backend Laravel untuk ARCHIVA: arsitektur berlapis, validasi, otorisasi, error, query Eloquent, dan testing. Dipakai saat mengubah route, controller, service, model, migration, middleware, policy, atau konfigurasi."
---

# Pedoman Backend ARCHIVA (Laravel · MySQL · shared hosting)

Kamu bertindak sebagai backend engineer senior yang menjaga sistem tetap mudah ditebak, mudah dirawat, dan aman. Dokumen ini melengkapi `archiva-laravel-stack`. Jika ada bentrok, `archiva-laravel-stack` yang menang.

## 1. Penilaian kelayakan sebelum mengubah backend (BFRI)

Nilai 1 sampai 5 untuk tiap dimensi.

| Dimensi | Pertanyaan |
|---|---|
| Kesesuaian arsitektur | Apakah mengikuti Route → Controller → Service → Model? |
| Kompleksitas logika bisnis | Seberapa rumit aturan domainnya? |
| Risiko data | Apakah menyentuh data surat, akun, atau transaksi multi-tabel? |
| Risiko operasional | Apakah memengaruhi login, hak akses, penyimpanan file, atau deploy? |
| Kemudahan diuji | Apakah bisa diuji dengan feature test dan unit test? |

Rumus: `BFRI = (Kesesuaian + Kemudahan diuji) − (Kompleksitas + Risiko data + Risiko operasional)`, rentang −10 sampai +10.

| BFRI | Arti | Tindakan |
|---|---|---|
| 6 sampai 10 | Aman | Lanjut |
| 3 sampai 5 | Sedang | Tambah test dan logging |
| 0 sampai 2 | Berisiko | Sederhanakan atau pecah tugas |
| di bawah 0 | Berbahaya | Rancang ulang sebelum menulis kode, tanya pemilik project |

Hitung BFRI hanya untuk perubahan yang menyentuh data, akses, atau file. Perubahan tampilan murni tidak perlu.

## 2. Doktrin arsitektur

1. **Alur berlapis**: `Route → Middleware → Controller → FormRequest → Service → Model (Eloquent) → Database`. Jangan melompati lapisan.
2. **Route hanya merutekan.** Tidak ada logika bisnis dan tidak ada query di `routes/web.php`.
3. **Controller tipis.** Terima request tervalidasi, panggil service, kembalikan view atau redirect. Tidak ada aturan bisnis di controller.
4. **Service memutuskan.** Aturan bisnis, penomoran, perubahan status, dan operasi multi-langkah ada di `app/Services/`. Dependensi disuntikkan lewat constructor. Repository tidak wajib, cukup Eloquent di dalam service. Buat repository hanya bila query yang sama dipakai banyak tempat dan rumit.
5. **Validasi semua input eksternal** (body, query, route param, file upload) dengan `FormRequest`. Tanpa validasi dianggap bug.
6. **Otorisasi di server.** Middleware `role` untuk akses halaman, `Policy` untuk akses per data. Menyembunyikan menu bukan otorisasi.
7. **Konfigurasi.** `env()` hanya boleh dipanggil di dalam `config/*.php`. Di tempat lain pakai `config('...')`. Jangan menaruh rahasia di kode.
8. **Error dan log.** Biarkan exception handler Laravel bekerja. Untuk kegagalan yang ditangkap, catat dengan `Log::error('pesan', ['konteks' => ...])` lalu lempar ulang atau tampilkan pesan ramah berbahasa Indonesia. Dilarang `dd()`, `dump()`, `var_dump()`, `echo` debug, dan menelan exception diam-diam. Jangan mencatat password, token, atau isi surat.
9. **Eloquent dengan disiplin.** Pakai `with()` untuk relasi (hindari N+1), `paginate()` untuk daftar panjang, `DB::transaction()` untuk operasi yang mengubah lebih dari satu tabel (mis. simpan surat sekaligus disposisinya). Hindari raw SQL kecuali terpaksa dan selalu pakai binding parameter.
10. **File dan queue** mengikuti `archiva-laravel-stack` bagian 2 dan 5 (disk privat, pengunduhan lewat controller ber-Policy, queue database dengan cron, Redis opsional).

Contoh yang benar:

```php
// routes/web.php: hanya merutekan
Route::middleware(['auth', 'role:tata_usaha'])->group(function () {
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])
        ->name('surat-masuk.store');
});

// Controller tipis
public function store(StoreSuratMasukRequest $request, SuratMasukService $service)
{
    $surat = $service->register($request->validated(), $request->file('berkas'));

    return redirect()->route('surat-masuk.show', $surat)
        ->with('status', 'Surat berhasil disimpan.');
}
```

## 3. Struktur direktori

```
app/
├── Enums/            # Role, StatusSurat, dll.
├── Http/
│   ├── Controllers/
│   ├── Middleware/   # EnsureRole, dll.
│   └── Requests/     # FormRequest
├── Models/
├── Policies/
└── Services/         # logika bisnis
config/
database/
├── migrations/
├── factories/
└── seeders/
resources/views/
├── components/       # Blade component (x-logo, x-button, ...)
├── layouts/
└── dashboard/
routes/web.php
tests/
├── Feature/
└── Unit/
```

## 4. Penamaan

| Jenis | Konvensi | Contoh |
|---|---|---|
| Controller | `PascalCase` + `Controller` | `SuratMasukController` |
| Service | `PascalCase` + `Service` | `SuratMasukService` |
| FormRequest | `Aksi` + `Nama` + `Request` | `StoreSuratMasukRequest` |
| Policy | `Nama` + `Policy` | `SuratMasukPolicy` |
| Model | Istilah domain PRD, singular | `SuratMasuk`, `Disposisi` |
| Tabel | `snake_case`, set `$table` eksplisit | `surat_masuk` |
| Route name | `kebab-case` bertitik | `surat-masuk.store` |

## 5. Testing

Setiap perubahan yang menyentuh akses atau data wajib punya test.

* **Feature test** untuk route kritis: login, redirect per role, akses ditolak (403) untuk role yang salah, upload dan unduh file.
* **Unit test** untuk service yang punya aturan bisnis.
* Gunakan factory dan `RefreshDatabase`. Test tidak boleh bergantung pada data dari seeder lokal.
* Jalankan `php artisan test` sebelum menyatakan tugas selesai dan laporkan hasilnya apa adanya.

## 6. Anti-pattern (langsung ditolak)

* Logika bisnis atau query di route atau Blade.
* Controller gemuk yang berisi aturan bisnis.
* Input tanpa `FormRequest`.
* Otorisasi hanya dengan menyembunyikan menu.
* `env()` di luar `config/`.
* `dd()`, `dump()`, `console.log`-style debug yang tertinggal.
* N+1 query pada daftar surat.
* Menyimpan berkas surat di `public/`.
* Mengedit migration yang sudah pernah dijalankan di hosting.
* Mengandalkan proses yang berjalan terus (worker daemon, WebSocket) di shared hosting.

## 6b. Checklist sebelum menyatakan selesai

* BFRI sudah dihitung (bila relevan) dan hasilnya di atas 2.
* Lapisan arsitektur dihormati, controller tetap tipis.
* Input divalidasi, akses dijaga Policy atau middleware.
* Tidak ada `env()` di luar config, tidak ada debug tertinggal.
* Pesan untuk pengguna berbahasa Indonesia.
* Test ditulis dan `php artisan test` serta `./vendor/bin/pint` sudah dijalankan.

## Batasan

* Skill ini tidak menggantikan pengujian di lingkungan hosting asli maupun review ahli.
* Jika masukan, izin, atau kriteria keberhasilan belum jelas, berhenti dan tanya pemilik project.
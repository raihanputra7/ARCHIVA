# Akun Uji Coba (Seeder Lokal) ARCHIVA

Dokumen ini memuat daftar akun uji coba lokal untuk portal persuratan ARCHIVA SDN Jatipulo 07 Jakarta.

> [!NOTE]
> Akun di bawah ini dihasilkan melalui seeder lokal (`database/seeders/UserSeeder.php`) dan **hanya berlaku pada environment `local`** (`APP_ENV=local`).

---

## Kredensial Masuk (Login)

Kata sandi sementara untuk **seluruh akun contoh** adalah:
```text
archiva123
```

URL Login: `http://archiva.test/login`

---

## Daftar Akun per Peran (Role)

| No | Peran (Role) | Nama Pengguna | Alamat Email | Kata Sandi | Unit Kerja |
|:--:|---|---|---|:---:|:---:|
| 1 | **Admin** | Ahmad Fauzi | `admin@archiva.test` | `archiva123` | Administrator Sistem |
| 2 | **Kepala Sekolah** | Dra. Siti Nurhaliza, M.Pd. | `kepsek@archiva.test` | `archiva123` | Pimpinan Sekolah |
| 3 | **Tata Usaha** | Budi Santoso | `tu@archiva.test` | `archiva123` | Dapodik |
| 4 | **Guru** | Ibu Ratna Dewi, S.Pd. | `guru@archiva.test` | `archiva123` | Dewan Guru |
| 5 | **Tenaga Pendidik** | Pak Joko | `tendik@archiva.test` | `archiva123` | Tenaga Penunjang / Keamanan |

---

## Petunjuk Uji Coba

1. Buka browser dan arahkan ke alamat [http://archiva.test/login](http://archiva.test/login).
2. Di bagian bawah formulir (lingkungan lokal), Anda dapat mengklik salah satu tombol **"Masuk Cepat Uji Coba Peran"** untuk otomatis mengisi alamat email peran yang diinginkan.
3. Masukkan kata sandi: `archiva123`.
4. Klik tombol **"Masuk ke Sistem"**.
5. Sistem akan otomatis me-redirect Anda ke dashboard yang sesuai dengan hak akses peran akun tersebut:
   - Admin: `/dashboard/admin`
   - Kepala Sekolah: `/dashboard/kepala-sekolah`
   - Tata Usaha: `/dashboard/tata-usaha`
   - Guru: `/dashboard/guru`
   - Tenaga Pendidik: `/dashboard/tenaga-pendidik`


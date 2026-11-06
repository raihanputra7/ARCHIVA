---
name: before-you-build
description: "Cek risiko produk sebelum membangun fitur baru yang belum ada di PRD: siapa pengguna, apa yang dipakai sekarang, kenapa mau pindah. Dilewati untuk fitur yang sudah tercantum di PRD ARCHIVA."
---

# Before You Build

## Penyesuaian untuk ARCHIVA

ARCHIVA adalah project PKM dengan mitra nyata (SDN Jatipulo 07), kebutuhannya sudah divalidasi dan dituangkan di `PRD-NARATIVE.md`. Karena itu:

* **Lewati skill ini** untuk fitur yang sudah ada di PRD (login per role, surat masuk, disposisi, arsip, tracking, surat keluar, laporan, manajemen pengguna). Langsung kerjakan sesuai instruksi pemilik project.
* **Pakai skill ini** hanya bila pemilik project meminta fitur baru yang tidak ada di PRD. Pada kasus itu, ajukan paling banyak 3 pertanyaan yang benar-benar mengubah keputusan membangun, lalu lanjut.
* Jangan pernah menghambat pekerjaan dengan checklist panjang. Skala pertanyaan mengikuti ukuran project: ini sistem internal sekolah, bukan startup.

## Gambaran

Skill ini membantu berhenti sejenak sebelum implementasi dan memeriksa apakah sebuah fitur layak dibangun. Fokusnya risiko produk, bukan struktur kode: siapa yang membutuhkannya, apa yang dipakai sekarang, kenapa mereka mau pindah, dan bukti apa yang membuat proyek lebih aman dimulai.

## Cara kerja

1. **Tentukan taruhan pembangunan.** Rumuskan fitur dalam satu kalimat konkret: pengguna yang dituju (role mana), pekerjaan yang ingin mereka selesaikan, dan cara mereka mengerjakannya sekarang (mis. grup WhatsApp, arsip fisik, file di komputer).
2. **Periksa risiko utama.** Kebutuhan nyata, kecocokan dengan alur kerja sekolah, kesediaan pengguna berpindah, beban operasional di shared hosting, dan akses data. Utamakan keraguan yang spesifik, bukan brainstorming umum.
3. **Tentukan uji kecil berikutnya.** Contoh: tanya langsung ke Tata Usaha atau Kepala Sekolah, buat prototipe halaman saja, atau uji coba manual dulu.
4. **Lanjut atau berhenti.** Jika risikonya wajar, tuliskan asumsinya lalu implementasikan. Jika bukti lemah, sarankan eksperimen yang lebih kecil.

## Contoh

Permintaan: "Tambahkan fitur chat antar pengguna."

Sebelum membangun, cek:
* Role mana yang butuh, dan sekarang mereka memakai apa (grup WhatsApp)?
* Keputusan atau pekerjaan apa yang berubah dengan adanya fitur ini?
* Apakah berjalan tanpa proses persisten di shared hosting?
* Apakah bisa diuji dulu dengan versi paling sederhana (komentar pada disposisi)?

## Praktik baik

* Tanyakan pengguna, pekerjaan, alternatif saat ini, dan alasan berpindah sebelum implementasi.
* Pisahkan risiko produk dari risiko teknis agar tidak menyelesaikan masalah yang salah dengan baik.
* Sarankan uji kecil bila bukti kebutuhan lemah.
* Jangan mengarang angka, tren, kutipan pengguna, atau klaim. Pakai hanya yang diberikan pemilik project.
* Jangan menyajikan checklist umum sebagai bukti bahwa ide sudah tervalidasi.

## Jebakan umum

* **Masalah:** asisten hanya mengulang pitch fitur. **Solusi:** tanyakan alternatif saat ini dan pemicu berpindah.
* **Masalah:** review terlalu luas dan menghalangi progres. **Solusi:** pilih asumsi paling berisiko dan uji itu saja.
* **Masalah:** fitur kecil diperlakukan seperti startup. **Solusi:** skala review mengikuti ukuran fitur.

## Batasan

* Tidak menggantikan riset pengguna, tinjauan hukum, atau review ahli.
* Tidak bisa membuktikan kebutuhan sendirian, hanya membantu memunculkan asumsi dan memilih langkah validasi yang lebih kecil.
* Jika pemilik project sudah punya bukti kuat dan spesifikasi jelas, singkat saja dan langsung implementasi.
---
name: andru-ia-solutions-architect-hybrid-engine-v-0
description: "Diagnosis awal workspace: tentukan apakah project baru atau sudah ada, lalu beri ringkasan teknis singkat. Dipakai hanya jika pemilik project secara eksplisit meminta diagnosis atau audit workspace."
---

# Andru.ia Solutions Architect (Hybrid Engine)

## Kapan dipakai

Hanya saat pemilik project secara eksplisit meminta diagnosis awal atau audit workspace, misalnya "diagnosa project ini" atau "audit workspace ini". Jangan aktif otomatis di awal setiap sesi.

Untuk ARCHIVA, stack dan cakupan sudah ditetapkan di `archiva-laravel-stack`, `DESIGN_SYSTEM.md`, dan `PRD-NARATIVE.md`. Skill ini tidak boleh mengubah atau menggantinya.

## Penyesuaian untuk ARCHIVA

1. **Bahasa**: seluruh komunikasi dalam bahasa Indonesia.
2. **Tidak membuat dokumen rencana.** Jangan membuat `tareas.md`, `plan_implementacion.md`, roadmap, atau backlog lain kecuali pemilik project memintanya. Hasil diagnosis disampaikan di chat.
3. **Tidak mengusulkan stack lain.** Stack tetap Laravel, MySQL, Blade, Tailwind, Alpine.js.
4. **Tidak membentuk "squad" ahli.** Gunakan hanya skill yang benar-benar ada di `.agents/skills/`.
5. **Konteks per folder.** Jangan mencampur data dari project lain.

## Alur

1. **Deteksi workspace.** Apakah folder kosong atau sudah berisi kode? Jika ada `artisan` dan `composer.json`, ini project Laravel yang sudah ada.
2. **Jika project sudah ada**, lakukan pemindaian teknis: versi Laravel dan PHP, struktur folder, migration, route, penyimpangan dari aturan di `archiva-laravel-stack`, dan utang teknis yang terlihat.
3. **Tanya seperlunya** (maksimal 3 pertanyaan, hanya yang belum terjawab oleh PRD dan design system): apa yang ingin diperbaiki atau ditambah, apa titik sakit terbesar saat ini, dan standar kualitas yang diinginkan.
4. **Beri "Preskripsi Teknis" singkat** di chat (sekitar 10 baris): temuan, risiko, dan langkah yang disarankan, urut dari yang paling penting.

## Aturan emas

1. Setiap folder adalah entitas tersendiri, jangan membawa asumsi dari project lain.
2. Utamakan solusi yang aman, mudah dirawat, dan berjalan di shared hosting (tanpa proses persisten).

## Batasan

* Gunakan skill ini hanya bila tugas jelas sesuai lingkup di atas.
* Hasilnya bukan pengganti pengujian di lingkungan asli atau review ahli.
* Jika masukan, izin, batas keamanan, atau kriteria keberhasilan belum jelas, berhenti dan tanya.
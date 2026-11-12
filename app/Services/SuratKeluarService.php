<?php

namespace App\Services;

use App\Models\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SuratKeluarService
{
    /**
     * Daftar kelompok klasifikasi persuratan resmi Kepgub DKI Jakarta No. 1598 Tahun 2021.
     */
    public function getKlasifikasiGroups(): array
    {
        return config('klasifikasi.groups', []);
    }

    /**
     * Daftar datar kode => detail klasifikasi untuk pencarian cepat / Alpine.js.
     */
    public function getFlatKlasifikasiOptions(): array
    {
        $flat = [];
        foreach ($this->getKlasifikasiGroups() as $groupCode => $group) {
            foreach ($group['items'] ?? [] as $code => $item) {
                $flat[$code] = [
                    'group' => $groupCode,
                    'nama' => $item['nama'],
                    'keterangan' => $item['keterangan'] ?? '',
                ];
            }
        }

        return $flat;
    }

    /**
     * Mendaftarkan surat keluar baru beserta berkas fisik privat.
     */
    public function register(array $data, ?UploadedFile $file, User $user): SuratKeluar
    {
        return DB::transaction(function () use ($data, $file, $user) {
            $nomorAgenda = $this->generateNomorAgenda();
            $year = ! empty($data['tanggal_surat'])
                ? (int) Carbon::parse($data['tanggal_surat'])->format('Y')
                : (int) Carbon::now()->format('Y');

            $nomorSurat = isset($data['nomor_surat']) ? trim((string) $data['nomor_surat']) : null;
            $kodeKlasifikasi = ! empty($data['kode_klasifikasi'])
                ? strtoupper(trim((string) $data['kode_klasifikasi']))
                : ($this->extractKodeKlasifikasi($nomorSurat) ?? (string) config('archiva.surat_keluar.default_klasifikasi', 'PK.01.01'));

            if (empty($nomorSurat)) {
                $nomorSurat = $this->generateNomorSurat($kodeKlasifikasi, $year);
            }

            $fileMetadata = [];
            if ($file) {
                // Simpan di disk local privat: storage/app/private/surat_keluar
                $storedPath = $file->store('surat_keluar', 'local');
                $fileMetadata = [
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_mime' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ];
            }

            return SuratKeluar::create(array_merge($data, $fileMetadata, [
                'nomor_agenda' => $nomorAgenda,
                'kode_klasifikasi' => $kodeKlasifikasi,
                'nomor_surat' => $nomorSurat,
                'status' => 'Draf',
                'created_by' => $user->id,
            ]));
        });
    }

    /**
     * Menyetujui surat keluar oleh Kepala Sekolah.
     */
    public function approve(SuratKeluar $suratKeluar, User $user): SuratKeluar
    {
        $suratKeluar->update([
            'status' => 'Disetujui',
            'approved_by' => $user->id,
            'approved_at' => Carbon::now(),
        ]);

        return $suratKeluar;
    }

    /**
     * Menghitung nomor urut surat keluar berikutnya pada tahun tertentu.
     * Pada tahun berjalan awal (2026), urutan dimulai/dilanjutkan dari nomor 125.
     */
    public function getNextNomorUrut(?int $year = null): int
    {
        $targetYear = $year ?? (int) Carbon::now()->format('Y');
        $startNumber = (int) config('archiva.surat_keluar.nomor_urut_mulai', 125);
        $startYear = (int) config('archiva.surat_keluar.tahun_mulai', 2026);

        $existingNumbers = SuratKeluar::query()
            ->whereNotNull('nomor_surat')
            ->whereYear('tanggal_surat', $targetYear)
            ->pluck('nomor_surat');

        $maxExisting = 0;
        foreach ($existingNumbers as $nomorSurat) {
            if (preg_match('/^(\d+)\/[A-Z]{2}(?:\.\d{2}){0,2}(?:\/|$)/', trim((string) $nomorSurat), $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxExisting) {
                    $maxExisting = $num;
                }
            }
        }

        $baselineLast = ($targetYear === $startYear) ? max(0, $startNumber - 1) : 0;

        return max($baselineLast, $maxExisting) + 1;
    }

    /**
     * Menghasilkan nomor surat keluar resmi berformat Kepgub DKI 1598/2021: {URUT}/{KLASIFIKASI}
     */
    public function generateNomorSurat(string $kodeKlasifikasi = 'PK.01.01', ?int $year = null): string
    {
        $urut = $this->getNextNomorUrut($year);
        $kode = strtoupper(trim($kodeKlasifikasi));

        return str_replace(
            ['{URUT}', '{KLASIFIKASI}'],
            [(string) $urut, $kode],
            (string) config('archiva.surat_keluar.nomor_format', '{URUT}/{KLASIFIKASI}')
        );
    }

    /**
     * Mengekstrak kode klasifikasi Kepgub dari string nomor surat jika formatnya sesuai (mis. 125/PK.01.01).
     */
    public function extractKodeKlasifikasi(?string $nomorSurat): ?string
    {
        if (! $nomorSurat) {
            return null;
        }

        if (preg_match('/^\d+\/([A-Z]{2}(?:\.\d{2}){0,2})(?:\/|$)/', trim($nomorSurat), $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Menghasilkan nomor agenda otomatis berurutan: ARC/SK/YYYYMM/0001
     */
    public function generateNomorAgenda(): string
    {
        $prefix = 'ARC/SK/'.Carbon::now()->format('Ym').'/';

        $lastSurat = SuratKeluar::where('nomor_agenda', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        if ($lastSurat) {
            $lastNumber = (int) substr($lastSurat->nomor_agenda, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix.$nextNumber;
    }
}

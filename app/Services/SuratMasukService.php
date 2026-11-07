<?php

namespace App\Services;

use App\Enums\Role;
use App\Enums\StatusSurat;
use App\Models\RiwayatStatus;
use App\Models\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SuratMasukService
{
    /**
     * Mendaftarkan surat masuk baru, menyimpan berkas privat, menghubungkan penerima,
     * dan mencatat baris riwayat status pertama dalam satu transaksi database.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int>  $penerimaIds
     */
    public function register(array $data, ?UploadedFile $file, User $user, array $penerimaIds = []): SuratMasuk
    {
        return DB::transaction(function () use ($data, $file, $user, $penerimaIds) {
            $year = isset($data['tanggal_diterima'])
                ? (int) Carbon::parse($data['tanggal_diterima'])->format('Y')
                : (int) Carbon::now()->format('Y');

            $nomorAgenda = $this->generateNomorAgenda($year);

            $fileMetadata = [];
            if ($file) {
                $disk = config('archiva.upload.disk', 'local');
                $storagePath = config('archiva.upload.storage_path', 'surat_masuk');

                // Disimpan di storage/app/private/surat_masuk dengan hashName acak
                $storedPath = $file->store($storagePath, $disk);

                $fileMetadata = [
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_mime' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ];
            }

            $nomorSurat = $data['nomor_surat'] ?? $data['nomor_surat_asli'] ?? null;
            $ringkasan = $data['ringkasan'] ?? $data['catatan'] ?? null;
            $asal = $data['asal'] ?? 'eksternal';

            /** @var SuratMasuk $surat */
            $surat = SuratMasuk::create(array_merge($data, $fileMetadata, [
                'nomor_agenda' => $nomorAgenda,
                'nomor_surat' => $nomorSurat,
                'nomor_surat_asli' => $nomorSurat,
                'ringkasan' => $ringkasan,
                'catatan' => $ringkasan,
                'asal' => $asal,
                'status' => StatusSurat::DITERIMA->value,
                'created_by' => $user->id,
                'dicatat_oleh' => $user->id,
            ]));

            // Jika daftar penerima kosong, default arahkan ke Kepala Sekolah
            if (empty($penerimaIds)) {
                $kepsekId = User::where('role', Role::KEPALA_SEKOLAH)->value('id');
                if ($kepsekId) {
                    $penerimaIds = [(int) $kepsekId];
                }
            }

            if (! empty($penerimaIds)) {
                $surat->penerima()->sync($penerimaIds);
            }

            // Catat baris pertama audit trail riwayat status
            RiwayatStatus::create([
                'surat_masuk_id' => $surat->id,
                'dari_status' => null,
                'ke_status' => StatusSurat::DITERIMA->value,
                'user_id' => $user->id,
                'catatan' => 'Surat masuk dicatat oleh Tata Usaha ('.$user->name.') dan diarsipkan ke dalam sistem.',
                'created_at' => Carbon::now(),
            ]);

            return $surat;
        });
    }

    /**
     * Menghasilkan nomor agenda otomatis berurutan per tahun dengan DB locking (lockForUpdate).
     */
    public function generateNomorAgenda(?int $year = null): string
    {
        $year = $year ?? (int) Carbon::now()->format('Y');
        $prefix = (string) config('archiva.nomor_agenda.prefix', 'ARC/SM');
        $padLength = (int) config('archiva.nomor_agenda.pad_length', 4);
        $format = (string) config('archiva.nomor_agenda.format', '{PREFIX}/{YEAR}/{NUMBER}');

        $prefixPattern = str_replace(
            ['{PREFIX}', '{YEAR}', '{NUMBER}'],
            [$prefix, (string) $year, ''],
            $format
        );

        // Kunci baris tertinggi dengan lockForUpdate untuk mencegah race condition
        $lastSurat = SuratMasuk::where('nomor_agenda', 'like', $prefixPattern.'%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        if ($lastSurat && preg_match('/(\d+)$/', $lastSurat->nomor_agenda, $matches)) {
            $lastNumber = (int) $matches[1];
            $nextNumber = str_pad((string) ($lastNumber + 1), $padLength, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = str_pad('1', $padLength, '0', STR_PAD_LEFT);
        }

        return str_replace(
            ['{PREFIX}', '{YEAR}', '{NUMBER}'],
            [$prefix, (string) $year, $nextNumber],
            $format
        );
    }
}

<?php

namespace App\Services;

use App\Models\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SuratMasukService
{
    /**
     * Mendaftarkan surat masuk baru beserta berkas fisik privat.
     */
    public function register(array $data, ?UploadedFile $file, User $user): SuratMasuk
    {
        return DB::transaction(function () use ($data, $file, $user) {
            $nomorAgenda = $this->generateNomorAgenda();

            $fileMetadata = [];
            if ($file) {
                // Simpan di disk local privat: storage/app/private/surat_masuk
                $storedPath = $file->store('surat_masuk', 'local');
                $fileMetadata = [
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_mime' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ];
            }

            return SuratMasuk::create(array_merge($data, $fileMetadata, [
                'nomor_agenda' => $nomorAgenda,
                'status' => 'Diterima',
                'created_by' => $user->id,
            ]));
        });
    }

    /**
     * Menghasilkan nomor agenda otomatis berurutan: ARC/SM/YYYYMM/0001
     */
    public function generateNomorAgenda(): string
    {
        $prefix = 'ARC/SM/'.Carbon::now()->format('Ym').'/';

        $lastSurat = SuratMasuk::where('nomor_agenda', 'like', $prefix.'%')
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

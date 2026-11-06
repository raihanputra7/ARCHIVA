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
     * Mendaftarkan surat keluar baru beserta berkas fisik privat.
     */
    public function register(array $data, ?UploadedFile $file, User $user): SuratKeluar
    {
        return DB::transaction(function () use ($data, $file, $user) {
            $nomorAgenda = $this->generateNomorAgenda();

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

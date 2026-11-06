<?php

namespace App\Services;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DisposisiService
{
    /**
     * Membuat lembar disposisi baru untuk surat masuk.
     */
    public function createDisposisi(SuratMasuk $surat, array $data, User $fromUser): Disposisi
    {
        return DB::transaction(function () use ($surat, $data, $fromUser) {
            $disposisi = Disposisi::create([
                'surat_masuk_id' => $surat->id,
                'from_user_id' => $fromUser->id,
                'to_user_id' => $data['to_user_id'],
                'instruksi' => $data['instruksi'],
                'sifat' => $data['sifat'] ?? 'Biasa',
                'batas_waktu' => $data['batas_waktu'] ?? null,
                'status' => 'Menunggu',
            ]);

            // Update status surat masuk menjadi 'Didisposisikan'
            $surat->update(['status' => 'Didisposisikan']);

            return $disposisi;
        });
    }

    /**
     * Memperbarui tindak lanjut disposisi oleh penerima.
     */
    public function updateTindakLanjut(Disposisi $disposisi, array $data): Disposisi
    {
        return DB::transaction(function () use ($disposisi, $data) {
            $updateData = [
                'status' => $data['status'],
                'catatan_tindak_lanjut' => $data['catatan_tindak_lanjut'],
            ];

            if ($data['status'] === 'Selesai') {
                $updateData['selesai_pada'] = now();
            }

            $disposisi->update($updateData);

            // Periksa apakah surat masuk perlu diubah statusnya
            $surat = $disposisi->suratMasuk;
            if ($surat) {
                if ($data['status'] === 'Selesai') {
                    // Jika semua disposisi selesai, tandai surat Selesai
                    $hasUnfinished = $surat->disposisi()->where('status', '!=', 'Selesai')->exists();
                    if (! $hasUnfinished) {
                        $surat->update(['status' => 'Selesai']);
                    }
                } elseif ($data['status'] === 'Diproses') {
                    $surat->update(['status' => 'Diproses']);
                }
            }

            return $disposisi;
        });
    }
}

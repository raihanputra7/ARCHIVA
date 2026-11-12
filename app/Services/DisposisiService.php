<?php

namespace App\Services;

use App\Enums\StatusSurat;
use App\Models\Disposisi;
use App\Models\RiwayatStatus;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\Notifications\NotificationDispatcher;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisposisiService
{
    public function __construct(
        private readonly ?NotificationDispatcher $dispatcher = null
    ) {}

    /**
     * Membuat lembar disposisi baru untuk surat masuk (mendukung multi-assignee secara atomic).
     *
     * @param  array<string, mixed>  $data
     * @return Collection<int, Disposisi>
     */
    public function createDisposisi(SuratMasuk $surat, array $data, User $fromUser): Collection
    {
        $createdDisposisi = DB::transaction(function () use ($surat, $data, $fromUser) {
            $recipientIds = [];
            if (! empty($data['to_user_ids']) && is_array($data['to_user_ids'])) {
                $recipientIds = array_values(array_unique(array_filter($data['to_user_ids'])));
            } elseif (! empty($data['to_user_id'])) {
                $recipientIds = [(int) $data['to_user_id']];
            }

            if (empty($recipientIds)) {
                throw new \InvalidArgumentException('Minimal harus ada satu penerima disposisi yang dipilih.');
            }

            $createdDisposisi = collect();
            $sifat = $data['sifat'] ?? 'Biasa';
            $batasWaktu = $data['batas_waktu'] ?? null;
            $instruksi = $data['instruksi'];

            foreach ($recipientIds as $toUserId) {
                $disposisi = Disposisi::create([
                    'surat_masuk_id' => $surat->id,
                    'from_user_id' => $fromUser->id,
                    'to_user_id' => $toUserId,
                    'instruksi' => $instruksi,
                    'sifat' => $sifat,
                    'batas_waktu' => $batasWaktu,
                    'status' => 'Menunggu',
                ]);

                $createdDisposisi->push($disposisi);
            }

            // Simpan status lama untuk audit trail riwayat status
            $statusLama = $surat->status;

            // Update status surat masuk menjadi 'Didisposisikan'
            $surat->update(['status' => StatusSurat::DIDISPOSISIKAN->value]);

            // Ambil nama seluruh penerima untuk pencatatan log riwayat status
            $recipients = User::whereIn('id', $recipientIds)->pluck('name')->all();
            $recipientsText = implode(', ', $recipients);

            RiwayatStatus::create([
                'surat_masuk_id' => $surat->id,
                'dari_status' => $statusLama instanceof StatusSurat ? $statusLama->value : (string) $statusLama,
                'ke_status' => StatusSurat::DIDISPOSISIKAN->value,
                'user_id' => $fromUser->id,
                'catatan' => "Disposisi ({$sifat}) diteruskan oleh {$fromUser->name} kepada: {$recipientsText}.",
                'created_at' => Carbon::now(),
            ]);

            return $createdDisposisi;
        });

        try {
            ($this->dispatcher ?? app(NotificationDispatcher::class))
                ->dispatchDisposisiCreated($surat, $createdDisposisi);
        } catch (\Throwable $e) {
            Log::warning('Gagal memicu notifikasi disposisi.', [
                'surat_masuk_id' => $surat->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $createdDisposisi;
    }

    /**
     * Memperbarui tindak lanjut disposisi oleh penerima.
     */
    public function updateTindakLanjut(Disposisi $disposisi, array $data): Disposisi
    {
        return DB::transaction(function () use ($disposisi, $data) {
            $updateData = [
                'status' => $data['status'],
                'catatan_tindak_lanjut' => $data['catatan_tindak_lanjut'] ?? null,
            ];

            if ($data['status'] === 'Selesai') {
                $updateData['selesai_pada'] = now();
            }

            $disposisi->update($updateData);

            // Periksa apakah surat masuk perlu diubah statusnya
            $surat = $disposisi->suratMasuk;
            if ($surat) {
                $statusAwal = $surat->status;
                $penerima = $disposisi->toUser;
                $namaPenerima = $penerima ? $penerima->name : 'Penerima';

                if ($data['status'] === 'Selesai') {
                    // Jika semua disposisi selesai, tandai surat Selesai
                    $hasUnfinished = $surat->disposisi()->where('status', '!=', 'Selesai')->exists();
                    if (! $hasUnfinished) {
                        $surat->update(['status' => StatusSurat::SELESAI->value]);

                        RiwayatStatus::create([
                            'surat_masuk_id' => $surat->id,
                            'dari_status' => $statusAwal instanceof StatusSurat ? $statusAwal->value : (string) $statusAwal,
                            'ke_status' => StatusSurat::SELESAI->value,
                            'user_id' => $disposisi->to_user_id,
                            'catatan' => "Tindak lanjut disposisi diselesaikan oleh {$namaPenerima}. Seluruh disposisi tuntas.",
                            'created_at' => Carbon::now(),
                        ]);
                    } else {
                        RiwayatStatus::create([
                            'surat_masuk_id' => $surat->id,
                            'dari_status' => $statusAwal instanceof StatusSurat ? $statusAwal->value : (string) $statusAwal,
                            'ke_status' => $statusAwal instanceof StatusSurat ? $statusAwal->value : (string) $statusAwal,
                            'user_id' => $disposisi->to_user_id,
                            'catatan' => "Tindak lanjut disposisi diselesaikan oleh {$namaPenerima}. Masih menunggu disposisi penerima lain.",
                            'created_at' => Carbon::now(),
                        ]);
                    }
                } elseif ($data['status'] === 'Diproses') {
                    if ($statusAwal !== StatusSurat::DIPROSES->value) {
                        $surat->update(['status' => StatusSurat::DIPROSES->value]);

                        RiwayatStatus::create([
                            'surat_masuk_id' => $surat->id,
                            'dari_status' => $statusAwal instanceof StatusSurat ? $statusAwal->value : (string) $statusAwal,
                            'ke_status' => StatusSurat::DIPROSES->value,
                            'user_id' => $disposisi->to_user_id,
                            'catatan' => "Disposisi mulai diproses dan ditindaklanjuti oleh {$namaPenerima}.",
                            'created_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            return $disposisi;
        });
    }
}

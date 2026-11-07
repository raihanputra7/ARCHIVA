<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisi';

    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'ke_user_id',
        'from_user_id',
        'to_user_id',
        'instruksi',
        'sifat',
        'batas_waktu',
        'status',
        'status_tindak_lanjut',
        'catatan_tindak_lanjut',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'batas_waktu' => 'date',
            'selesai_pada' => 'datetime',
        ];
    }

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function dariUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function keUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function isTerlambat(): bool
    {
        $status = strtolower((string) ($this->status_tindak_lanjut ?? $this->status));

        return $status !== 'selesai' && $this->batas_waktu && $this->batas_waktu->isPast();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat_asli',
        'pengirim',
        'perihal',
        'tanggal_surat',
        'tanggal_diterima',
        'kategori',
        'sifat',
        'status',
        'catatan',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_diterima' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    public function latestDisposisi()
    {
        return $this->hasOne(Disposisi::class, 'surat_masuk_id')->latestOfMany();
    }
}

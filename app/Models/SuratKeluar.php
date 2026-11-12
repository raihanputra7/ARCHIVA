<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_agenda',
        'kode_klasifikasi',
        'parent_surat_masuk_id',
        'nomor_surat',
        'tujuan',
        'perihal',
        'tanggal_surat',
        'kategori',
        'sifat',
        'status',
        'ringkasan',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Surat masuk yang menjadi rujukan / asal balasan naskah keluar ini.
     */
    public function parentSuratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'parent_surat_masuk_id');
    }

    /**
     * Mengambil nama klasifikasi arsip berdasarkan kode Kepgub DKI No. 1598 Tahun 2021.
     */
    public function getNamaKlasifikasiAttribute(): ?string
    {
        if (! $this->kode_klasifikasi) {
            return null;
        }

        foreach (config('klasifikasi.groups', []) as $group) {
            if (isset($group['items'][$this->kode_klasifikasi]['nama'])) {
                return $group['items'][$this->kode_klasifikasi]['nama'];
            }
        }

        return null;
    }
}

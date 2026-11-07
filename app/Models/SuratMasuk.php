<?php

namespace App\Models;

use App\Enums\StatusSurat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'nomor_surat_asli',
        'pengirim',
        'perihal',
        'ringkasan',
        'catatan',
        'tanggal_surat',
        'tanggal_diterima',
        'asal',
        'kategori',
        'sifat',
        'status',
        'file_path',
        'file_name',
        'file_mime',
        'file_size',
        'created_by',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_diterima' => 'date',
        ];
    }

    /**
     * Petugas TU yang mencatat surat masuk.
     */
    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Pengguna yang dituju sebagai penerima surat masuk (Pivot: surat_penerima).
     */
    public function penerima(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'surat_penerima', 'surat_masuk_id', 'user_id')
            ->withPivot('dibaca_pada')
            ->withTimestamps();
    }

    /**
     * Riwayat disposisi pimpinan terhadap surat ini.
     */
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    /**
     * Seluruh naskah dinas surat keluar yang menjadi balasan dari surat masuk ini.
     */
    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'parent_surat_masuk_id');
    }

    public function suratBalasan(): HasMany
    {
        return $this->suratKeluar();
    }

    public function latestDisposisi(): HasOne
    {
        return $this->hasOne(Disposisi::class, 'surat_masuk_id')->latestOfMany();
    }

    /**
     * Audit trail linimasa perubahan status surat.
     */
    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class, 'surat_masuk_id')->orderBy('created_at', 'asc');
    }

    public function latestRiwayat(): HasOne
    {
        return $this->hasOne(RiwayatStatus::class, 'surat_masuk_id')->latestOfMany('id');
    }

    /**
     * Mengecek apakah surat masuk telah melewati batas waktu tindak lanjut disposisi (Status Terlambat dihitung).
     */
    public function isTerlambat(): bool
    {
        $statusValue = strtolower((string) ($this->status instanceof StatusSurat ? $this->status->value : $this->status));

        if ($statusValue === StatusSurat::SELESAI->value) {
            return false;
        }

        return $this->disposisi->contains(function (Disposisi $disp) {
            $dispStatus = strtolower((string) ($disp->status_tindak_lanjut ?? $disp->status));

            return $dispStatus !== 'selesai' && $disp->batas_waktu && $disp->batas_waktu->isPast();
        });
    }

    /**
     * Accessor untuk nomor surat resmi (dengan fallback nomor_surat_asli).
     */
    public function getNomorSuratDisplayAttribute(): string
    {
        return $this->nomor_surat ?? $this->nomor_surat_asli ?? '(Tidak bernomor)';
    }

    /**
     * Accessor untuk ringkasan (dengan fallback catatan).
     */
    public function getRingkasanDisplayAttribute(): ?string
    {
        return $this->ringkasan ?? $this->catatan;
    }

    /**
     * Helper status enum instance.
     */
    public function statusEnum(): ?StatusSurat
    {
        return StatusSurat::tryFrom(strtolower((string) $this->status));
    }
}

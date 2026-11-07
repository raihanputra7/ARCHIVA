<?php

namespace App\Http\Requests;

use App\Models\SuratMasuk;
use Illuminate\Foundation\Http\FormRequest;

class StoreSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SuratMasuk::class) ?? false;
    }

    public function rules(): array
    {
        $maxSizeKb = config('archiva.upload.max_size_kb', 5120);

        return [
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'nomor_surat_asli' => ['nullable', 'string', 'max:100'],
            'asal' => ['required', 'in:eksternal,internal'],
            'pengirim' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:500'],
            'ringkasan' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_diterima' => ['required', 'date'],
            'kategori' => ['required', 'string', 'max:100'],
            'sifat' => ['required', 'in:Biasa,Segera,Kilat,Rahasia'],
            'penerima_ids' => ['nullable', 'array'],
            'penerima_ids.*' => ['integer', 'exists:users,id'],
            'berkas' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:'.$maxSizeKb],
        ];
    }

    public function messages(): array
    {
        return [
            'asal.required' => 'Asal surat (eksternal atau internal) wajib dipilih.',
            'asal.in' => 'Asal surat harus berupa eksternal atau internal.',
            'pengirim.required' => 'Asal instansi atau nama pengirim surat wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'tanggal_diterima.required' => 'Tanggal diterima surat wajib diisi.',
            'kategori.required' => 'Kategori naskah surat wajib dipilih.',
            'sifat.required' => 'Sifat urgensi surat wajib dipilih.',
            'berkas.mimes' => 'Berkas pindaian surat harus berformat PDF, JPG, atau PNG.',
            'berkas.max' => 'Ukuran berkas lampiran maksimal 5 MB.',
        ];
    }
}

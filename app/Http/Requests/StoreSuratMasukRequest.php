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
        return [
            'nomor_surat_asli' => ['nullable', 'string', 'max:100'],
            'pengirim' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:500'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_diterima' => ['required', 'date'],
            'kategori' => ['required', 'string', 'max:100'],
            'sifat' => ['required', 'in:Biasa,Segera,Kilat,Rahasia'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'berkas' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'pengirim.required' => 'Asal atau pengirim surat wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'tanggal_diterima.required' => 'Tanggal diterima surat wajib diisi.',
            'kategori.required' => 'Kategori surat wajib dipilih.',
            'berkas.mimes' => 'Berkas lampiran harus berformat PDF, JPG, atau PNG.',
            'berkas.max' => 'Ukuran berkas lampiran maksimal 5 MB.',
        ];
    }
}

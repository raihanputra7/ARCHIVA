<?php

namespace App\Http\Requests;

use App\Models\SuratKeluar;
use Illuminate\Foundation\Http\FormRequest;

class StoreSuratKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SuratKeluar::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'parent_surat_masuk_id' => ['nullable', 'integer', 'exists:surat_masuk,id'],
            'nomor_surat' => ['nullable', 'string', 'max:100'],
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:500'],
            'tanggal_surat' => ['required', 'date'],
            'kategori' => ['required', 'string', 'max:100'],
            'sifat' => ['required', 'in:Biasa,Segera,Kilat,Rahasia'],
            'ringkasan' => ['nullable', 'string', 'max:1000'],
            'berkas' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'tujuan.required' => 'Pihak tujuan surat keluar wajib diisi.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'kategori.required' => 'Kategori surat wajib dipilih.',
            'berkas.mimes' => 'Berkas lampiran harus berformat PDF, JPG, atau PNG.',
            'berkas.max' => 'Ukuran berkas lampiran maksimal 5 MB.',
        ];
    }
}

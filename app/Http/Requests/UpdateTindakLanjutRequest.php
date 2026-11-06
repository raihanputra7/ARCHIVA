<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTindakLanjutRequest extends FormRequest
{
    public function authorize(): bool
    {
        $disposisi = $this->route('disposisi');

        return $this->user()?->can('updateStatus', $disposisi) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:Diproses,Selesai'],
            'catatan_tindak_lanjut' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status tindak lanjut wajib dipilih.',
            'catatan_tindak_lanjut.required' => 'Uraian catatan tindak lanjut wajib diisi.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Disposisi;
use Illuminate\Foundation\Http\FormRequest;

class StoreDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Disposisi::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'to_user_ids' => ['required_without:to_user_id', 'nullable', 'array', 'min:1'],
            'to_user_ids.*' => ['integer', 'exists:users,id'],
            'to_user_id' => ['required_without:to_user_ids', 'nullable', 'integer', 'exists:users,id'],
            'instruksi' => ['required', 'string', 'max:1000'],
            'sifat' => ['required', 'in:Biasa,Segera,Kilat'],
            'batas_waktu' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'to_user_ids.required_without' => 'Penerima disposisi wajib dipilih minimal satu orang.',
            'to_user_ids.min' => 'Penerima disposisi wajib dipilih minimal satu orang.',
            'to_user_ids.*.exists' => 'Salah satu penerima disposisi yang dipilih tidak ditemukan di sistem.',
            'to_user_id.required_without' => 'Penerima disposisi wajib dipilih.',
            'to_user_id.exists' => 'Penerima disposisi tidak ditemukan di sistem.',
            'instruksi.required' => 'Instruksi atau catatan disposisi wajib diisi.',
            'sifat.required' => 'Tingkat urgensi disposisi wajib dipilih.',
        ];
    }
}

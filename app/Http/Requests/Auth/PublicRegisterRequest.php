<?php

namespace App\Http\Requests\Auth;

use App\Services\RegistrationService;
use App\Support\PhoneNormalizer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => Str::lower(trim((string) $this->input('email'))),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:users,email'],
            'phone_wa' => [
                'required',
                'string',
                'max:25',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! PhoneNormalizer::isValid((string) $value)) {
                        $fail('Nomor WhatsApp harus valid dalam format 08xx, 62xx, atau +62xx.');
                    }
                },
            ],
            'requested_role' => [
                'required',
                'string',
                Rule::in(RegistrationService::allowedPublicRoles()),
            ],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Pendaftaran dengan alamat email ini tidak dapat diproses.',
            'phone_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'requested_role.required' => 'Peran yang diajukan wajib dipilih.',
            'requested_role.in' => 'Peran yang dipilih tidak tersedia untuk pendaftaran mandiri.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}

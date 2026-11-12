<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Support\PhoneNormalizer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone_wa' => [
                'nullable',
                'string',
                'max:25',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== null && trim((string) $value) !== '' && ! PhoneNormalizer::isValid((string) $value)) {
                        $fail('Nomor WhatsApp harus valid dalam format 08xx, 62xx, atau +62xx.');
                    }
                },
            ],
            'notify_email' => ['nullable', 'boolean'],
        ];
    }
}

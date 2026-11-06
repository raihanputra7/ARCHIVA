@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
])

@php
    $inputId = $id ?? ($name ?? 'input-' . \Illuminate\Support\Str::random(6));
    $hasError = !empty($error);
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-on-surface mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-error" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface placeholder:text-outline border transition-colors ' .
                    ($hasError
                        ? 'border-error focus:border-error focus:ring-2 focus:ring-error focus:ring-offset-2'
                        : 'border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:ring-offset-2') .
                    ' disabled:opacity-50 disabled:bg-surface-container-low disabled:cursor-not-allowed focus:outline-none'
            ]) }}
        />
    </div>

    @if($hasError)
        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-error font-medium" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ $error }}</span>
        </div>
    @elseif($hint)
        <p class="mt-1.5 text-xs text-on-surface-variant">{{ $hint }}</p>
    @endif
</div>


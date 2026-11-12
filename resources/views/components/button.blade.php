@props([
    'variant' => 'primary', // 'primary' | 'accent' | 'secondary' | 'ghost' | 'danger'
    'size' => 'md',        // 'sm' | 'md' | 'lg'
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseStyles = 'inline-flex items-center justify-center font-semibold rounded-lg transition-colors duration-150 select-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 min-h-[44px] md:min-h-0';

    $sizeStyles = match($size) {
        'sm' => 'h-9 px-3 text-xs gap-1.5',
        'lg' => 'h-12 px-6 text-base gap-2.5',
        default => 'h-10 px-4 text-sm gap-2',
    };

    $variantStyles = match($variant) {
        'accent' => 'bg-secondary text-on-secondary hover:brightness-95 focus-visible:ring-secondary shadow-sm',
        'secondary' => 'border border-primary text-primary bg-transparent hover:bg-primary-container focus-visible:ring-primary',
        'ghost' => 'text-on-surface bg-transparent hover:bg-surface-container focus-visible:ring-outline',
        'danger' => 'bg-error text-on-error hover:opacity-95 focus-visible:ring-error shadow-sm',
        default => 'bg-primary text-on-primary hover:bg-primary-strong focus-visible:ring-primary shadow-sm',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    @if($type === 'submit')
        x-data="{ submitting: false }"
        x-init="$el.closest('form')?.addEventListener('submit', (e) => { if (!e.defaultPrevented) submitting = true; })"
        :class="{ 'opacity-80 pointer-events-none': submitting }"
    @endif
    {{ $attributes->merge(['class' => "{$baseStyles} {$sizeStyles} {$variantStyles}"]) }}
>
    @if($type === 'submit')
        <x-spinner x-show="submitting" x-cloak size="xs" />
    @endif
    {{ $slot }}
</button>


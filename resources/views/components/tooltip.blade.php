@props([
    'text' => '',
    'position' => 'top',
    'when' => 'true',
])

@php
    $posClasses = match($position) {
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        default => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    };
@endphp

<div
    x-data="{ tip: false }"
    @mouseenter="tip = true"
    @mouseleave="tip = false"
    @focusin="tip = true"
    @focusout="tip = false"
    {{ $attributes->merge(['class' => 'relative inline-flex']) }}
>
    {{ $slot }}

    @if($text !== '')
        <div
            x-show="tip && ({{ $when }})"
            x-cloak
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            role="tooltip"
            class="pointer-events-none absolute {{ $posClasses }} z-50 whitespace-nowrap px-2.5 py-1 rounded-lg bg-on-surface text-surface text-[11px] font-semibold shadow-md"
        >
            {{ $text }}
        </div>
    @endif
</div>

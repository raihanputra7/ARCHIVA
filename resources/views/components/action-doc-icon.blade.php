@props([
    'href' => null,
    'label' => 'Detail Surat',
    'target' => null,
])

@php
    $classes = 'inline-flex items-center justify-center w-10 h-10 rounded-lg border border-border-subtle bg-surface-lowest text-on-surface hover:bg-surface-container hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest';
@endphp

@if($href)
    <x-tooltip :text="$label" position="left">
        <a
            href="{{ $href }}"
            @if($target) target="{{ $target }}" rel="noopener noreferrer" @endif
            aria-label="{{ $label }}"
            {{ $attributes->merge(['class' => $classes]) }}
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </a>
    </x-tooltip>
@else
    <x-tooltip :text="$label" position="left">
        <button
            type="button"
            disabled
            aria-label="{{ $label }}"
            {{ $attributes->merge(['class' => $classes . ' opacity-50 cursor-not-allowed']) }}
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
        </button>
    </x-tooltip>
@endif


@props([
    'align' => 'left',
    'width' => 'w-72',
])

@php
    $alignClass = $align === 'right' ? 'right-0' : 'left-0';
@endphp

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    {{ $attributes->merge(['class' => 'relative inline-block']) }}
>
    <div @click="open = !open" @mouseenter="open = true" @mouseleave="open = false">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute z-40 mt-2 {{ $alignClass }} {{ $width }} p-3.5 rounded-xl bg-surface-lowest border border-border-subtle shadow-xl text-xs text-on-surface"
    >
        {{ $slot }}
    </div>
</div>


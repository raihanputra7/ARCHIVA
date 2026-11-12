@props([
    'name',
    'title' => 'Opsi & Filter',
])

<div
    x-data="{ open: false }"
    x-on:open-sheet.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-sheet.window="if ($event.detail === '{{ $name }}') open = false"
    @keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[65] md:hidden flex flex-col justify-end"
    role="dialog"
    aria-modal="true"
>
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 z-0 bg-navy-dark/60 backdrop-blur-[1px]"
    ></div>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-250"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="relative z-10 w-full max-h-[85vh] overflow-y-auto bg-surface-lowest border-t border-border-subtle rounded-t-3xl shadow-2xl p-5 space-y-4"
    >
        <div class="w-10 h-1 rounded-full bg-outline-variant mx-auto"></div>

        <div class="flex items-center justify-between gap-3">
            <h3 class="text-sm font-bold text-on-surface">{{ $title }}</h3>
            <button
                type="button"
                @click="open = false"
                class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors"
                aria-label="Tutup"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>
</div>


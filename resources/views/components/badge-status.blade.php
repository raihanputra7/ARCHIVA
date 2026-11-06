@props([
    'status' => 'Diterima',
])

@php
    $normalized = strtolower(trim($status));

    $config = match($normalized) {
        'didisposisikan' => [
            'class' => 'bg-secondary-container text-on-secondary-container border border-secondary/30',
            'label' => 'Didisposisikan',
            'icon' => 'M13 5l7 7-7 7M5 5l7 7-7 7', // double arrow forward
        ],
        'diproses' => [
            'class' => 'bg-surface-container-high text-on-surface border border-outline-variant',
            'label' => 'Diproses',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', // clock
        ],
        'selesai' => [
            'class' => 'bg-success-container text-on-success-container border border-success/30',
            'label' => 'Selesai',
            'icon' => 'M5 13l4 4L19 7', // checkmark
        ],
        'terlambat' => [
            'class' => 'bg-error-container text-on-error-container border border-error/30',
            'label' => 'Terlambat',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', // alert
        ],
        'ditolak' => [
            'class' => 'bg-error-container text-on-error-container border border-error/30',
            'label' => 'Ditolak',
            'icon' => 'M6 18L18 6M6 6l12 12', // x mark
        ],
        'draf' => [
            'class' => 'bg-surface-container text-on-surface-variant border border-border-subtle',
            'label' => 'Draf',
            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', // pencil
        ],
        default => [
            'class' => 'bg-primary-container text-on-primary-container border border-primary/20',
            'label' => 'Diterima',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', // check-circle
        ],
    };
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold leading-none select-none tracking-wide {$config['class']}"
]) }}>
    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
    </svg>
    <span>{{ $config['label'] }}</span>
</span>


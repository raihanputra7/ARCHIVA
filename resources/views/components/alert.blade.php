@props([
    'type' => 'info',
    'title' => null,
])

@php
    $classes = match($type) {
        'success' => 'bg-success-container text-on-success-container border-success/30',
        'error' => 'bg-error-container text-on-error-container border-error/30',
        'warning' => 'bg-warning-container text-on-warning-container border-warning/30',
        default => 'bg-primary-container text-on-primary-container border-primary/20',
    };
@endphp

<div {{ $attributes->merge(['class' => "p-3.5 rounded-xl border text-xs leading-relaxed {$classes}"]) }} role="{{ $type === 'error' ? 'alert' : 'status' }}">
    @if($title)
        <p class="font-bold mb-1">{{ $title }}</p>
    @endif
    <div>{{ $slot }}</div>
</div>


@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => "bg-surface-lowest border border-border-subtle rounded-2xl shadow-sm text-on-surface {$padding} transition-colors"]) }}>
    @if($title || $action || $subtitle)
        <div class="flex items-start justify-between gap-4 mb-4 pb-3 border-b border-border-subtle">
            <div>
                @if($title)
                    <h3 class="text-lg font-bold text-on-surface leading-tight">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($action)
                <div class="flex-shrink-0">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>


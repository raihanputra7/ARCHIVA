@props([
    'type' => 'table',
    'rows' => 4,
])

@if($type === 'stats')
    <div {{ $attributes->merge(['class' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 animate-pulse']) }} aria-hidden="true">
        @for($i = 0; $i < 4; $i++)
            <div class="p-5 rounded-2xl border border-border-subtle bg-surface-lowest space-y-3">
                <div class="h-3 w-24 rounded bg-surface-container"></div>
                <div class="h-8 w-16 rounded bg-surface-container-high"></div>
                <div class="h-2.5 w-32 rounded bg-surface-container"></div>
            </div>
        @endfor
    </div>
@elseif($type === 'card')
    <div {{ $attributes->merge(['class' => 'p-5 rounded-2xl border border-border-subtle bg-surface-lowest space-y-3 animate-pulse']) }} aria-hidden="true">
        <div class="flex items-center justify-between gap-3">
            <div class="h-4 w-32 rounded bg-surface-container-high"></div>
            <div class="h-5 w-20 rounded-full bg-surface-container"></div>
        </div>
        <div class="h-4 w-3/4 rounded bg-surface-container"></div>
        <div class="h-3 w-1/2 rounded bg-surface-container"></div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-border-subtle bg-surface-lowest overflow-hidden animate-pulse']) }} aria-hidden="true">
        <div class="h-10 bg-surface-container-low border-b border-border-subtle px-6 flex items-center gap-6">
            <div class="h-3 w-20 rounded bg-surface-container"></div>
            <div class="h-3 w-40 rounded bg-surface-container"></div>
            <div class="h-3 w-24 rounded bg-surface-container ml-auto"></div>
        </div>
        <div class="divide-y divide-border-subtle">
            @for($i = 0; $i < $rows; $i++)
                <div class="px-6 py-4 flex items-center gap-6">
                    <div class="space-y-1.5 w-28">
                        <div class="h-3.5 w-24 rounded bg-surface-container-high"></div>
                        <div class="h-2.5 w-16 rounded bg-surface-container"></div>
                    </div>
                    <div class="space-y-1.5 flex-1">
                        <div class="h-3.5 w-2/3 rounded bg-surface-container-high"></div>
                        <div class="h-2.5 w-1/3 rounded bg-surface-container"></div>
                    </div>
                    <div class="h-6 w-20 rounded-full bg-surface-container"></div>
                    <div class="h-8 w-8 rounded-lg bg-surface-container"></div>
                </div>
            @endfor
        </div>
    </div>
@endif


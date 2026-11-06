@props([
    'variant' => 'default', // 'default' | 'on-dark'
    'size' => 'md',        // 'sm' (32px), 'md' (40px), 'lg' (48px)
    'showText' => true,
    'subtext' => 'Portal Persuratan SDN Jatipulo 07',
])

@php
    $heightClass = match($size) {
        'sm' => 'h-8',
        'lg' => 'h-12',
        default => 'h-10',
    };

    $iconBoxSize = match($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-10 h-10',
    };

    $isOnDark = $variant === 'on-dark';
@endphp

{{-- 
    DESIGN_SYSTEM.md Bagian 1:
    - Latar logo selalu terang (#FFFFFF).
    - Bila area sekitar gelap (termasuk mode gelap atau variant on-dark), WAJIB memakai panel putih rounded-xl p-2.
    - Ruang bebas minimal 0.5x tinggi logo di keempat sisi.
    - Ukuran minimum 32px (sm), default 40px (md).
    - Placeholder resmi digunakan karena logo asli sekolah belum tersedia di _legacy maupun public/images.
--}}
<div {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-3 select-none transition-colors ' . 
        ($isOnDark 
            ? 'bg-[#FFFFFF] text-[#0F1B2D] rounded-xl p-2 shadow-sm' 
            : 'dark:bg-[#FFFFFF] dark:text-[#0F1B2D] dark:rounded-xl dark:p-2 dark:shadow-sm')
]) }}>
    <div class="flex-shrink-0 flex items-center justify-center {{ $iconBoxSize }} rounded-lg bg-[#E3EAF6] text-[#1B3A6B] border border-[#C8CDD8]"
         title="Placeholder Logo Resmi SDN Jatipulo 07">
        <svg class="w-3/5 h-3/5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"></path>
            <path d="M6 6h10"></path>
            <path d="M6 10h10"></path>
            <path d="M6 14h6"></path>
            <path d="M18 2v20"></path>
        </svg>
    </div>

    @if($showText)
        <div class="flex flex-col text-left leading-tight">
            <div class="flex items-center gap-1.5">
                <span class="font-extrabold tracking-tight text-base {{ $isOnDark ? 'text-[#0F2347]' : 'text-on-surface dark:text-[#0F2347]' }}">ARCHIVA</span>
                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-[#FBF1D3] text-[#5C4710]">SDN 07</span>
            </div>
            @if($subtext)
                <span class="text-[11px] font-medium {{ $isOnDark ? 'text-[#4A5163]' : 'text-on-surface-variant dark:text-[#4A5163]' }} truncate max-w-[190px]">{{ $subtext }}</span>
            @endif
        </div>
    @endif
</div>

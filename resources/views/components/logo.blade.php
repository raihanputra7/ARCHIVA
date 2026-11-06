@props([
    'variant' => 'default', // 'default' | 'on-dark'
    'size' => 'md',        // 'sm' (32px), 'md' (40px), 'lg' (48px)
    'showText' => true,
    'subtext' => 'Portal Persuratan SDN Jatipulo 07',
])

@php
    $imgHeightClass = match($size) {
        'sm' => 'h-8',
        'lg' => 'h-12',
        default => 'h-10',
    };

    $isOnDark = $variant === 'on-dark';
@endphp

{{-- 
    DESIGN_SYSTEM.md Bagian 1: Aturan Logo (prioritas tertinggi)
    1. Latar logo selalu terang (#FFFFFF).
    2. Bila area sekitar gelap (mode gelap, panel kiri login, footer navy), WAJIB memakai panel putih rounded-xl p-2.
    3. Ruang bebas minimal 0.5x tinggi logo di keempat sisi.
    4. Ukuran minimum 32px (sm), default 40px (md).
    5. Aspect ratio dijaga (object-contain, w-auto), tidak di-stretch atau crop.
    6. Logo resmi SDN Jatipulo 07: public/images/logo-sdn-jatipulo07.png.
--}}
<div {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-3 select-none transition-colors ' . 
        ($isOnDark 
            ? 'bg-[#FFFFFF] text-[#0F1B2D] rounded-xl p-2 shadow-sm' 
            : 'dark:bg-[#FFFFFF] dark:text-[#0F1B2D] dark:rounded-xl dark:p-2 dark:shadow-sm')
]) }}>
    <div class="flex-shrink-0 flex items-center justify-center">
        <img
            src="{{ asset('images/logo-sdn-jatipulo07.png') }}"
            alt="Logo SDN Jatipulo 07"
            class="{{ $imgHeightClass }} w-auto object-contain flex-shrink-0"
            loading="eager"
        >
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

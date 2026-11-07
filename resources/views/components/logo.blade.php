@props([
    'variant' => 'default', // 'default' | 'on-dark'
    'size' => 'md',        // 'sm' (28px), 'md' (36px), 'lg' (44px)
    'showText' => true,
    'subtext' => 'Portal Persuratan SDN Jatipulo 07',
])

@php
    $imgHeightClass = match($size) {
        'sm' => 'h-7',
        'lg' => 'h-11',
        default => 'h-9',
    };

    $isOnDark = $variant === 'on-dark';
@endphp

{{-- 
    DESIGN_SYSTEM.md Bagian 1: Aturan Logo
    - Kontainer putih (bg-white rounded-lg p-1.5) HANYA membungkus aset gambar logo sekolah,
      agar lambang tetap kontras di latar gelap tanpa membungkus elemen teks.
    - Elemen teks berada langsung di atas permukaan latar dengan warna yang adaptif di mode terang & gelap.
--}}
<div {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-2.5 select-none min-w-0'
]) }}>
    {{-- Panel putih khusus untuk logo agar selalu terang dan kontras --}}
    <div class="flex-shrink-0 flex items-center justify-center bg-white rounded-lg p-1.5 shadow-xs border border-slate-200/60 dark:border-white/10">
        <img
            src="{{ asset('images/logo-sdn-jatipulo07.png') }}"
            alt="Logo SDN Jatipulo 07"
            class="{{ $imgHeightClass }} w-auto object-contain flex-shrink-0"
            loading="eager"
        >
    </div>

    @if($showText)
        <div class="flex flex-col text-left leading-tight min-w-0 flex-1">
            <div class="flex items-center gap-1.5">
                <span class="font-bold tracking-tight text-base {{ $isOnDark ? 'text-white' : 'text-slate-900 dark:text-white' }}">ARCHIVA</span>
                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">SDN 07</span>
            </div>
            @if($subtext)
                <span class="text-xs {{ $isOnDark ? 'text-slate-200' : 'text-slate-500 dark:text-slate-400' }} leading-tight w-auto flex-1 mt-0.5">{{ $subtext }}</span>
            @endif
        </div>
    @endif
</div>

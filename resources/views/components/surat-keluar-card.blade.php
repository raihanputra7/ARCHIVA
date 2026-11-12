@props([
    'item',
])

@php
    $surat = $item['surat'];
@endphp

<article class="bg-surface-lowest border border-border-subtle rounded-2xl shadow-sm p-4 sm:p-5 flex flex-col justify-between gap-3 hover:bg-surface-container-low transition-colors duration-150">
    <div class="space-y-2.5">
        {{-- Baris Atas: Nomor Surat & Status --}}
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <a
                    href="{{ $item['showUrl'] }}"
                    class="inline-block font-mono font-bold text-sm text-primary tabular-nums break-words hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
                >
                    {{ $surat->nomor_surat ?? '(Nomor belum terbit)' }}
                </a>
                <div class="text-xs font-mono text-on-surface-variant break-words tabular-nums">
                    {{ $surat->nomor_agenda }}
                </div>
            </div>
            <div class="flex-shrink-0">
                <x-badge-status :status="$surat->status" />
            </div>
        </div>

        {{-- Perihal --}}
        <div>
            <a
                href="{{ $item['showUrl'] }}"
                title="{{ $surat->perihal }}"
                class="font-bold text-[15px] leading-6 text-on-surface line-clamp-3 hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
            >
                {{ $surat->perihal }}
            </a>
        </div>

        {{-- Tujuan & Tanggal --}}
        <div class="text-[13px] leading-5 text-on-surface-variant space-y-0.5">
            <p class="line-clamp-2" title="{{ $surat->tujuan }}">
                <span class="font-semibold text-on-surface">Tujuan:</span> {{ $surat->tujuan }}
            </p>
            <p class="tabular-nums">
                {{ $item['tanggalLabel'] }}
            </p>
        </div>

        {{-- Kategori, Sifat & Berkas --}}
        <div class="flex flex-wrap items-center gap-2 pt-0.5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant leading-tight">
                {{ $surat->kategori }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold leading-tight {{ $item['sifatClass'] }}">
                {{ $surat->sifat }}
            </span>
            @if($item['downloadUrl'])
                <a
                    href="{{ $item['downloadUrl'] }}"
                    aria-label="Unduh berkas"
                    title="Unduh Berkas Lampiran"
                    class="inline-flex items-center text-primary hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                </a>
            @endif
        </div>
    </div>

    {{-- Aksi --}}
    <div class="pt-2 border-t border-border-subtle">
        <a
            href="{{ $item['showUrl'] }}"
            class="w-full md:w-auto inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 rounded-lg text-xs font-semibold border border-border-subtle bg-surface-lowest text-on-surface hover:bg-surface-container hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest"
        >
            <span>Rincian</span>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</article>


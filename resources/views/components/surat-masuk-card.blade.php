@props([
    'item',
])

@php
    $surat = $item['surat'];
@endphp

<article class="bg-surface-lowest border border-border-subtle rounded-2xl shadow-sm p-4 sm:p-5 flex flex-col justify-between gap-3 hover:bg-surface-container-low transition-colors duration-150">
    <div class="space-y-2.5">
        {{-- Baris Atas: Nomor Agenda & Status --}}
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <a
                    href="{{ $item['detailUrl'] }}"
                    class="inline-block font-mono font-bold text-sm text-primary tabular-nums break-words hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
                >
                    {{ $surat->nomor_agenda }}
                </a>
                @if($surat->nomor_surat_asli)
                    <div class="text-xs text-on-surface-variant break-words tabular-nums">
                        {{ $surat->nomor_surat_asli }}
                    </div>
                @endif
            </div>
            <div class="flex-shrink-0">
                <x-badge-status :status="$surat->status" />
            </div>
        </div>

        {{-- Perihal --}}
        <div>
            <a
                href="{{ $item['detailUrl'] }}"
                title="{{ $surat->perihal }}"
                class="font-bold text-[15px] leading-6 text-on-surface line-clamp-3 hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
            >
                {{ $surat->perihal }}
            </a>
        </div>

        {{-- Pengirim & Tanggal --}}
        <div class="text-[13px] leading-5 text-on-surface-variant space-y-0.5">
            <p class="line-clamp-2" title="{{ $surat->pengirim }}">
                <span class="font-semibold text-on-surface">Pengirim:</span> {{ $surat->pengirim }}
            </p>
            <p class="tabular-nums">
                {{ $item['tanggalLabel'] }}
            </p>
        </div>

        {{-- Kategori + Ikon Berkas --}}
        <div class="flex items-center gap-2 pt-0.5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant leading-tight">
                {{ $surat->kategori }}
            </span>
            @if($item['hasFile'])
                <span
                    class="inline-flex items-center text-on-surface-variant"
                    aria-label="Ada berkas"
                    title="Ada berkas"
                    role="img"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                </span>
            @endif
        </div>
    </div>

    {{-- Aksi --}}
    <div class="pt-2 border-t border-border-subtle">
        <a
            href="{{ $item['disposisiSheetUrl'] }}"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full md:w-auto inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 rounded-lg text-xs font-semibold border border-border-subtle bg-surface-lowest text-on-surface hover:bg-surface-container hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Lembar Disposisi</span>
        </a>
    </div>
</article>


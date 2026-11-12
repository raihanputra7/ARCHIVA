@props([
    'item',
])

@php
    $surat = $item['surat'];
@endphp

<tr class="border-b border-border-subtle last:border-b-0 hover:bg-surface-container-low transition-colors duration-150">
    {{-- Nomor Surat & Agenda --}}
    <td class="py-3 pl-6 pr-3 align-top text-[13px] leading-5">
        <a
            href="{{ $item['showUrl'] }}"
            class="block font-mono font-bold text-primary tabular-nums break-words hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
        >
            {{ $surat->nomor_surat ?? '(Nomor belum terbit)' }}
        </a>
        <div class="mt-0.5 text-xs font-mono text-on-surface-variant break-words tabular-nums">
            {{ $surat->nomor_agenda }}
        </div>
        @if($surat->kode_klasifikasi)
            <div class="mt-0.5 text-[11px] text-on-surface-variant line-clamp-1" title="{{ $surat->kode_klasifikasi }}{{ $surat->nama_klasifikasi ? ' — ' . $surat->nama_klasifikasi : '' }}">
                {{ $surat->kode_klasifikasi }}{{ $surat->nama_klasifikasi ? ' · ' . $surat->nama_klasifikasi : '' }}
            </div>
        @endif
    </td>

    {{-- Perihal, Kategori, Sifat & Berkas --}}
    <td class="py-3 px-3 align-top text-[13px] leading-5">
        <a
            href="{{ $item['showUrl'] }}"
            title="{{ $surat->perihal }}"
            class="font-bold text-on-surface line-clamp-2 hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest rounded"
        >
            {{ $surat->perihal }}
        </a>
        <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant leading-tight">
                {{ $surat->kategori }}
            </span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold leading-tight {{ $item['sifatClass'] }}">
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
        @if($surat->parentSuratMasuk)
            <div class="mt-1">
                <a
                    href="{{ route('surat-masuk.show', $surat->parentSuratMasuk) }}"
                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:underline bg-primary-container px-1.5 py-0.5 rounded"
                    title="Lihat Surat Masuk Asal"
                >
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    <span>Balasan: {{ $surat->parentSuratMasuk->nomor_agenda }}</span>
                </a>
            </div>
        @endif
    </td>

    {{-- Tujuan --}}
    <td class="py-3 px-3 align-top text-[13px] leading-5 text-on-surface">
        <span class="line-clamp-2 font-medium" title="{{ $surat->tujuan }}">
            {{ $surat->tujuan }}
        </span>
    </td>

    {{-- Tanggal --}}
    <td class="py-3 px-2.5 align-top text-[13px] leading-5 text-on-surface-variant tabular-nums">
        {{ $item['tanggalLabel'] }}
    </td>

    {{-- Status --}}
    <td class="py-3 px-2 align-top">
        <x-badge-status :status="$surat->status" />
    </td>

    {{-- Aksi --}}
    <td class="py-3 pl-1 pr-4 align-top text-right">
        <a
            href="{{ $item['showUrl'] }}"
            aria-label="Rincian Surat Keluar"
            title="Rincian Surat Keluar"
            class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-border-subtle bg-surface-lowest text-on-surface hover:bg-surface-container hover:text-primary transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface-lowest"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </td>
</tr>


<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Dashboard Tenaga Pendidik & Penunjang
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Penerimaan surat penugasan operasional, sarana prasarana, dan pengumuman kedinasan SDN Jatipulo 07
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI Metrik Tenaga Pendidik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($kpis as $kpi)
                <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                    <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ $kpi['label'] }}</span>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-on-surface tracking-tight tabular-nums">{{ $kpi['value'] }}</span>
                        <span class="text-xs font-medium text-on-surface-variant">{{ $kpi['unit'] }}</span>
                    </div>
                    <p class="text-[11px] text-outline mt-1">{{ $kpi['subtext'] }}</p>
                </x-card>
            @endforeach
        </div>

        <!-- 2. Tabel Instruksi Kerja & Naskah Penugasan Operasional -->
        @php
            $daftarInstruksi = collect($letters)->map(function ($letter) {
                $hasSurat = !empty($letter['surat_id']);

                return [
                    'id' => $letter['id'],
                    'number' => $letter['number'] ?? null,
                    'subject' => $letter['subject'],
                    'sender' => $letter['sender'],
                    'date' => $letter['date'],
                    'status' => $letter['status'],
                    'showUrl' => $hasSurat ? route('surat-masuk.show', $letter['surat_id']) : null,
                    'actionLabel' => $hasSurat ? 'Lihat Tugas' : 'Segera hadir',
                ];
            });
        @endphp

        <x-card title="Instruksi Kerja & Pengumuman Penugasan" subtitle="Surat tugas penunjang operasional sekolah dan sarana prasarana yang ditujukan kepada Anda">
            @if($daftarInstruksi->isEmpty())
                <div class="py-8 px-4 text-center text-on-surface-variant text-sm">
                    Belum ada instruksi kerja baru yang ditujukan kepada Anda.
                </div>
            @else
                <!-- Tampilan Desktop (>= 1280px): Tabel semantik tanpa scroll horizontal -->
                <div class="hidden xl:block -mx-6 -mb-6 overflow-hidden rounded-b-2xl">
                    <table class="w-full table-fixed text-left border-collapse">
                        <caption class="sr-only">Instruksi Kerja dan Pengumuman Penugasan</caption>
                        <colgroup>
                            <col style="width: 19%">
                            <col style="width: 37%">
                            <col style="width: 16%">
                            <col style="width: 20%">
                            <col style="width: 8%">
                        </colgroup>
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant text-xs leading-4 font-semibold uppercase tracking-[0.02em] border-y border-border-subtle">
                                <th scope="col" class="py-3 pl-6 pr-3">AGENDA</th>
                                <th scope="col" class="py-3 px-3">PERIHAL</th>
                                <th scope="col" class="py-3 px-3">TANGGAL</th>
                                <th scope="col" class="py-3 px-3">STATUS</th>
                                <th scope="col" class="py-3 pl-2 pr-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-on-surface">
                            @foreach($daftarInstruksi as $item)
                                <tr class="hover:bg-surface-container-low transition-colors duration-150">
                                    <td class="py-3 pl-6 pr-3 align-top text-[13px] leading-5">
                                        @if($item['showUrl'])
                                            <a
                                                href="{{ $item['showUrl'] }}"
                                                class="block font-mono font-bold text-primary tabular-nums break-words hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 rounded"
                                            >
                                                {{ $item['id'] }}
                                            </a>
                                        @else
                                            <span class="block font-mono font-bold text-primary tabular-nums break-words">
                                                {{ $item['id'] }}
                                            </span>
                                        @endif
                                        @if($item['number'])
                                            <div class="mt-0.5 text-xs text-on-surface-variant break-words tabular-nums">
                                                {{ $item['number'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 align-top text-[13px] leading-5">
                                        @if($item['showUrl'])
                                            <a
                                                href="{{ $item['showUrl'] }}"
                                                title="{{ $item['subject'] }}"
                                                class="font-bold text-on-surface line-clamp-2 hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 rounded"
                                            >
                                                {{ $item['subject'] }}
                                            </a>
                                        @else
                                            <p title="{{ $item['subject'] }}" class="font-bold text-on-surface line-clamp-2">
                                                {{ $item['subject'] }}
                                            </p>
                                        @endif
                                        <div class="mt-0.5 text-xs text-on-surface-variant line-clamp-1" title="{{ $item['sender'] }}">
                                            {{ $item['sender'] }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 align-top text-[13px] leading-5 text-on-surface-variant tabular-nums">
                                        {{ $item['date'] }}
                                    </td>
                                    <td class="py-3 px-3 align-top">
                                        <x-badge-status :status="$item['status']" />
                                    </td>
                                    <td class="py-3 pl-2 pr-6 align-top text-right">
                                        <x-action-doc-icon :href="$item['showUrl']" :label="$item['actionLabel']" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan Mobile & Tablet (< 1280px): Daftar kartu -->
                <div class="xl:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($daftarInstruksi as $item)
                        <article class="bg-surface-lowest border border-border-subtle rounded-2xl shadow-sm p-4 flex flex-col justify-between gap-3 hover:bg-surface-container-low transition-colors">
                            <div class="space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        @if($item['showUrl'])
                                            <a href="{{ $item['showUrl'] }}" class="font-mono font-bold text-sm text-primary tabular-nums break-words hover:underline">
                                                {{ $item['id'] }}
                                            </a>
                                        @else
                                            <span class="font-mono font-bold text-sm text-primary tabular-nums break-words">
                                                {{ $item['id'] }}
                                            </span>
                                        @endif
                                        @if($item['number'])
                                            <div class="text-xs text-on-surface-variant break-words tabular-nums">
                                                {{ $item['number'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-badge-status :status="$item['status']" />
                                    </div>
                                </div>

                                @if($item['showUrl'])
                                    <a href="{{ $item['showUrl'] }}" title="{{ $item['subject'] }}" class="block font-bold text-[15px] leading-6 text-on-surface line-clamp-2 hover:text-primary transition-colors">
                                        {{ $item['subject'] }}
                                    </a>
                                @else
                                    <p title="{{ $item['subject'] }}" class="font-bold text-[15px] leading-6 text-on-surface line-clamp-2">
                                        {{ $item['subject'] }}
                                    </p>
                                @endif

                                <div class="text-[13px] leading-5 text-on-surface-variant space-y-0.5">
                                    <p class="line-clamp-1" title="{{ $item['sender'] }}">
                                        <span class="font-semibold text-on-surface">Pengirim:</span> {{ $item['sender'] }}
                                    </p>
                                    <p class="tabular-nums">{{ $item['date'] }}</p>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-border-subtle flex items-center justify-end">
                                <x-action-doc-icon :href="$item['showUrl']" :label="$item['actionLabel']" />
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </x-card>

        <!-- 3. Repositori Sarana Prasarana & Tata Tertib Sekolah -->
        <x-card title="Arsip Sarana Prasarana & Fasilitas Sekolah" subtitle="Dokumen panduan operasional gedung 4 lantai dan penunjang kegiatan sekolah">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                @foreach($repositories as $repo)
                    <div class="p-4 rounded-xl border border-border-subtle bg-surface-container-low/40 hover:bg-surface-container-low transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <span class="text-xs font-bold text-primary truncate">{{ $repo['title'] }}</span>
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-primary-container text-on-primary-container">{{ $repo['category'] }}</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-2 line-clamp-2 leading-relaxed">
                            {{ $repo['description'] }}
                        </p>
                        <div class="mt-3 pt-2 border-t border-border-subtle flex items-center justify-between text-[11px] text-outline font-medium">
                            <span>{{ $repo['files_count'] }} Dokumen</span>
                            <span>{{ $repo['updated_at'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</x-app-layout>

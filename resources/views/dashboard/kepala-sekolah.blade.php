<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Dashboard Kepala Sekolah
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Pemantauan naskah dinas, disposisi pimpinan, dan persetujuan surat keluar SDN Jatipulo 07
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI -->
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

        <!-- 2. Grafik & Visualisasi Tren Surat Masuk (Chart.js via Vite) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
            initCharts() {
                if (typeof Chart === 'undefined') return;

                // Tren Chart
                const trendCtx = document.getElementById('trendChartKepsek')?.getContext('2d');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: {{ json_encode($chartData['trend']['labels']) }},
                            datasets: [{
                                label: 'Surat Masuk',
                                data: {{ json_encode($chartData['trend']['data']) }},
                                borderColor: '#1B3A6B',
                                backgroundColor: 'rgba(27, 58, 107, 0.1)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointBackgroundColor: '#C9A84C',
                                pointBorderColor: '#FFFFFF',
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#E2E8F0' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                // Kategori Chart
                const catCtx = document.getElementById('categoryChartKepsek')?.getContext('2d');
                if (catCtx) {
                    new Chart(catCtx, {
                        type: 'doughnut',
                        data: {
                            labels: {{ json_encode($chartData['categories']['labels']) }},
                            datasets: [{
                                data: {{ json_encode($chartData['categories']['data']) }},
                                backgroundColor: ['#1B3A6B', '#C9A84C', '#0F2347', '#E8C97A', '#1E7B4F'],
                                borderWidth: 2,
                                borderColor: '#FFFFFF',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                            }
                        }
                    });
                }
            }
        }" x-init="initCharts()">
            <!-- Grafik Tren -->
            <x-card class="lg:col-span-2" title="Tren Surat Masuk" subtitle="Volume naskah dinas diterima 7 hari terakhir">
                <div class="h-64 w-full">
                    <canvas id="trendChartKepsek"></canvas>
                </div>
            </x-card>

            <!-- Grafik Komposisi Kategori -->
            <x-card title="Distribusi Kategori" subtitle="Persentase jenis naskah">
                <div class="h-64 w-full flex items-center justify-center">
                    <canvas id="categoryChartKepsek"></canvas>
                </div>
            </x-card>
        </div>

        <!-- 3. Tabel Surat Masuk & Antrean Disposisi Pimpinan -->
        @php
            $daftarAntrean = collect($letters)->map(function ($letter) {
                $disposisiTargets = $letter->disposisi
                    ->map(fn ($disp) => $disp->toUser?->name)
                    ->filter()
                    ->unique()
                    ->implode(', ');

                return [
                    'surat' => $letter,
                    'showUrl' => route('surat-masuk.show', $letter),
                    'tanggalLabel' => $letter->tanggal_diterima?->isoFormat('D MMM Y') ?? '-',
                    'disposisiTargets' => $disposisiTargets,
                    'actionLabel' => strtolower($letter->status) === 'diterima' ? 'Disposisi' : 'Detail',
                ];
            });
        @endphp

        <x-card title="Naskah Dinas & Antrean Disposisi" subtitle="Daftar surat resmi yang membutuhkan arahan atau tindak lanjut pimpinan">
            @if($daftarAntrean->isEmpty())
                <div class="py-10 px-4 text-center text-on-surface-variant text-sm">
                    Belum ada naskah dinas masuk yang tercatat.
                </div>
            @else
                <!-- Tampilan Desktop (>= 1280px): Tabel semantik tanpa scroll horizontal -->
                <div class="hidden xl:block -mx-6 -mb-6 overflow-hidden rounded-b-2xl">
                    <table class="w-full table-fixed text-left border-collapse">
                        <caption class="sr-only">Naskah Dinas dan Antrean Disposisi</caption>
                        <colgroup>
                            <col style="width: 17%">
                            <col style="width: 29%">
                            <col style="width: 21%">
                            <col style="width: 11%">
                            <col style="width: 15%">
                            <col style="width: 7%">
                        </colgroup>
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant text-xs leading-4 font-semibold uppercase tracking-[0.02em] border-y border-border-subtle">
                                <th scope="col" class="py-3 pl-6 pr-3">AGENDA</th>
                                <th scope="col" class="py-3 px-3">PERIHAL</th>
                                <th scope="col" class="py-3 px-3">TUJUAN</th>
                                <th scope="col" class="py-3 px-2.5">TANGGAL</th>
                                <th scope="col" class="py-3 px-2">STATUS</th>
                                <th scope="col" class="py-3 pl-2 pr-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-on-surface">
                            @foreach($daftarAntrean as $item)
                                @php($letter = $item['surat'])
                                <tr class="hover:bg-surface-container-low transition-colors duration-150">
                                    <td class="py-3 pl-6 pr-3 align-top text-[13px] leading-5">
                                        <a
                                            href="{{ $item['showUrl'] }}"
                                            class="block font-mono font-bold text-primary tabular-nums break-words hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 rounded"
                                        >
                                            {{ $letter->nomor_agenda }}
                                        </a>
                                        @if($letter->nomor_surat_asli)
                                            <div class="mt-0.5 text-xs text-on-surface-variant break-words tabular-nums">
                                                {{ $letter->nomor_surat_asli }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 align-top text-[13px] leading-5">
                                        <a
                                            href="{{ $item['showUrl'] }}"
                                            title="{{ $letter->perihal }}"
                                            class="font-bold text-on-surface line-clamp-2 hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 rounded"
                                        >
                                            {{ $letter->perihal }}
                                        </a>
                                        <div class="mt-0.5 text-xs text-on-surface-variant line-clamp-1" title="{{ $letter->pengirim }}">
                                            {{ $letter->pengirim }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 align-top text-[13px] leading-5 text-on-surface-variant">
                                        @if($item['disposisiTargets'])
                                            <span class="line-clamp-2 font-medium text-on-surface" title="{{ $item['disposisiTargets'] }}">
                                                {{ $item['disposisiTargets'] }}
                                            </span>
                                        @else
                                            <span class="italic">Belum didisposisikan</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-2.5 align-top text-[13px] leading-5 text-on-surface-variant tabular-nums">
                                        {{ $item['tanggalLabel'] }}
                                    </td>
                                    <td class="py-3 px-2 align-top">
                                        <x-badge-status :status="$letter->status" />
                                    </td>
                                    <td class="py-3 pl-2 pr-6 align-top text-right">
                                        <x-action-doc-icon :href="$item['showUrl']" :label="$item['actionLabel'] . ' Surat Masuk'" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan Mobile & Tablet (< 1280px): Daftar kartu -->
                <div class="xl:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($daftarAntrean as $item)
                        @php($letter = $item['surat'])
                        <article class="bg-surface-lowest border border-border-subtle rounded-2xl shadow-sm p-4 flex flex-col justify-between gap-3 hover:bg-surface-container-low transition-colors">
                            <div class="space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ $item['showUrl'] }}" class="font-mono font-bold text-sm text-primary tabular-nums break-words hover:underline">
                                            {{ $letter->nomor_agenda }}
                                        </a>
                                        @if($letter->nomor_surat_asli)
                                            <div class="text-xs text-on-surface-variant break-words tabular-nums">
                                                {{ $letter->nomor_surat_asli }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-badge-status :status="$letter->status" />
                                    </div>
                                </div>

                                <a href="{{ $item['showUrl'] }}" title="{{ $letter->perihal }}" class="block font-bold text-[15px] leading-6 text-on-surface line-clamp-2 hover:text-primary transition-colors">
                                    {{ $letter->perihal }}
                                </a>

                                <div class="text-[13px] leading-5 text-on-surface-variant space-y-0.5">
                                    <p class="line-clamp-1" title="{{ $letter->pengirim }}">
                                        <span class="font-semibold text-on-surface">Pengirim:</span> {{ $letter->pengirim }}
                                    </p>
                                    <p class="line-clamp-1" title="{{ $item['disposisiTargets'] ?: 'Belum didisposisikan' }}">
                                        <span class="font-semibold text-on-surface">Tujuan:</span> {{ $item['disposisiTargets'] ?: 'Belum didisposisikan' }}
                                    </p>
                                    <p class="tabular-nums">{{ $item['tanggalLabel'] }}</p>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-border-subtle">
                                <a
                                    href="{{ $item['showUrl'] }}"
                                    class="w-full md:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2 rounded-lg text-xs font-semibold bg-surface-container text-on-surface hover:bg-primary-container hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                >
                                    {{ $item['actionLabel'] }}
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>

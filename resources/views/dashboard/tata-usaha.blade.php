<x-app-layout active="dashboard">
    <x-slot name="header">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                Dashboard Tata Usaha
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                Registrasi surat masuk dinas, penomoran berkas otomatis, dan pengarsipan digital SDN Jatipulo 07
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI Metrik Tata Usaha -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($kpis as $kpi)
                @php
                    $isLongValue = strlen((string) $kpi['value']) > 8;
                @endphp
                <x-card class="min-w-0 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ $kpi['label'] }}</span>
                    <div class="mt-4 flex flex-wrap items-baseline gap-x-2 gap-y-1 min-w-0">
                        <span
                            class="{{ $isLongValue ? 'text-lg sm:text-xl font-bold font-mono break-all' : 'text-3xl font-extrabold tabular-nums' }} text-on-surface tracking-tight"
                            title="{{ $kpi['value'] }}"
                        >
                            {{ $kpi['value'] }}
                        </span>
                        <span class="text-xs font-medium text-on-surface-variant">{{ $kpi['unit'] }}</span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $kpi['subtext'] }}</p>
                </x-card>
            @endforeach
        </div>

        <!-- 2. Grafik Tren & Kategori Surat Masuk (Chart.js via Vite) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
            initCharts() {
                if (typeof Chart === 'undefined') return;

                const isDark = document.documentElement.classList.contains('dark');
                const tickColor = isDark ? '#AAB4C8' : '#4A5163';
                const gridColor = isDark ? '#22345A' : '#E2E8F0';
                const cardBorder = isDark ? '#101F3D' : '#FFFFFF';

                const trendCtx = document.getElementById('trendChartTU')?.getContext('2d');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'bar',
                        data: {
                            labels: {{ json_encode($chartData['trend']['labels']) }},
                            datasets: [{
                                label: 'Surat Masuk',
                                data: {{ json_encode($chartData['trend']['data']) }},
                                backgroundColor: isDark ? '#A9C0EC' : '#1B3A6B',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor } },
                                x: { grid: { display: false }, ticks: { color: tickColor } }
                            }
                        }
                    });
                }

                const catCtx = document.getElementById('categoryChartTU')?.getContext('2d');
                if (catCtx) {
                    new Chart(catCtx, {
                        type: 'doughnut',
                        data: {
                            labels: {{ json_encode($chartData['categories']['labels']) }},
                            datasets: [{
                                data: {{ json_encode($chartData['categories']['data']) }},
                                backgroundColor: ['#1B3A6B', '#C9A84C', '#0F2347', '#E8C97A', '#1E7B4F'],
                                borderWidth: 2,
                                borderColor: cardBorder,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '58%',
                            layout: {
                                padding: { top: 4, bottom: 4 }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        padding: 12,
                                        color: tickColor,
                                        font: { size: 11 }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }" x-init="initCharts()">
            <x-card class="lg:col-span-2 min-w-0" title="Tren Surat Masuk per Tanggal Penerimaan" subtitle="Aktivitas pencatatan naskah masuk oleh loket TU">
                <div class="h-72 w-full relative">
                    <canvas id="trendChartTU"></canvas>
                </div>
            </x-card>

            <x-card class="min-w-0" title="Distribusi Kategori Surat" subtitle="Proporsi kelompok perihal dokumen">
                <div class="h-72 w-full relative flex items-center justify-center">
                    <canvas id="categoryChartTU"></canvas>
                </div>
            </x-card>
        </div>

        <!-- 3. Tabel Surat Masuk Terbaru -->
        @php
            $daftarTerbaru = collect($letters)->map(fn ($letter) => [
                'surat' => $letter,
                'showUrl' => route('surat-masuk.show', $letter),
                'tanggalLabel' => $letter->tanggal_diterima?->isoFormat('D MMM Y') ?? '-',
            ]);
        @endphp

        <x-card title="Surat Masuk Terbaru" subtitle="Daftar berkas naskah dinas yang baru dicatat dan diarsipkan">
            @if($daftarTerbaru->isEmpty())
                <div class="py-10 px-4 text-center text-on-surface-variant text-sm">
                    Belum ada surat masuk yang tercatat.
                </div>
            @else
                <!-- Tampilan Desktop (>= 1280px): Tabel semantik tanpa scroll horizontal -->
                <div class="hidden xl:block -mx-6 -mb-6 overflow-hidden rounded-b-2xl">
                    <table class="w-full table-fixed text-left border-collapse">
                        <caption class="sr-only">Surat Masuk Terbaru</caption>
                        <colgroup>
                            <col style="width: 18%">
                            <col style="width: 29%">
                            <col style="width: 21%">
                            <col style="width: 11%">
                            <col style="width: 14%">
                            <col style="width: 7%">
                        </colgroup>
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant text-xs leading-4 font-semibold uppercase tracking-[0.02em] border-y border-border-subtle">
                                <th scope="col" class="py-3 pl-6 pr-3">AGENDA</th>
                                <th scope="col" class="py-3 px-3">PERIHAL</th>
                                <th scope="col" class="py-3 px-3">PENGIRIM</th>
                                <th scope="col" class="py-3 px-2.5">TANGGAL</th>
                                <th scope="col" class="py-3 px-2">STATUS</th>
                                <th scope="col" class="py-3 pl-2 pr-6 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-on-surface">
                            @foreach($daftarTerbaru as $item)
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
                                        <span class="mt-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant leading-tight">
                                            {{ $letter->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 align-top text-[13px] leading-5 text-on-surface">
                                        <span class="line-clamp-2 font-medium" title="{{ $letter->pengirim }}">
                                            {{ $letter->pengirim }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2.5 align-top text-[13px] leading-5 text-on-surface-variant tabular-nums">
                                        {{ $item['tanggalLabel'] }}
                                    </td>
                                    <td class="py-3 px-2 align-top">
                                        <x-badge-status :status="$letter->status" />
                                    </td>
                                    <td class="py-3 pl-2 pr-6 align-top text-right">
                                        <x-action-doc-icon :href="$item['showUrl']" label="Detail Surat Masuk" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan Mobile & Tablet (< 1280px): Daftar kartu -->
                <div class="xl:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($daftarTerbaru as $item)
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
                                    <p class="tabular-nums">{{ $item['tanggalLabel'] }}</p>
                                </div>

                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-on-surface-variant leading-tight">
                                        {{ $letter->kategori }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-border-subtle">
                                <a
                                    href="{{ $item['showUrl'] }}"
                                    class="w-full md:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2 rounded-lg text-xs font-semibold bg-surface-container text-on-surface hover:bg-primary-container hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                >
                                    Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </x-card>

        <!-- 4. Repositori Arsip Digital (2x2 Grid) -->
        <x-card title="Repositori Arsip Digital" subtitle="Penyimpanan berkas surat dan dokumen dinas terverifikasi">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                @foreach($repositories as $repo)
                    <div class="min-w-0 p-4 rounded-xl border border-border-subtle bg-surface-container-low/40 hover:bg-surface-container-low transition-colors flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-xs font-bold text-primary line-clamp-1" title="{{ $repo['title'] }}">{{ $repo['title'] }}</span>
                                <span class="flex-shrink-0 text-[10px] font-semibold px-1.5 py-0.5 rounded bg-primary-container text-on-primary-container">{{ $repo['category'] }}</span>
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-2 line-clamp-2 leading-relaxed">
                                {{ $repo['description'] }}
                            </p>
                        </div>
                        <div class="mt-3 pt-2 border-t border-border-subtle flex items-center justify-between gap-2 text-[11px] text-on-surface-variant font-medium">
                            <span class="tabular-nums">{{ $repo['files_count'] }} Dokumen</span>
                            <span class="truncate">{{ $repo['updated_at'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</x-app-layout>

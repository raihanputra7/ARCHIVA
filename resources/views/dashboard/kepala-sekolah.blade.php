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
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-secondary-container text-on-secondary-container border border-secondary/30">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>Pimpinan Sekolah</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($kpis as $kpi)
                <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ $kpi['label'] }}</span>
                        <span class="text-xs font-bold {{ $kpi['trend'] === 'up' ? 'text-success' : 'text-primary' }} bg-surface-container px-2 py-0.5 rounded-full">
                            {{ $kpi['delta'] }}
                        </span>
                    </div>
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
        <x-card title="Naskah Dinas & Antrean Disposisi" subtitle="Daftar surat resmi yang membutuhkan arahan atau tindak lanjut pimpinan">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Agenda / Surat</th>
                            <th class="py-3 px-6">Pengirim & Perihal</th>
                            <th class="py-3 px-6">Tanggal Masuk</th>
                            <th class="py-3 px-6">Tujuan / Disposisi</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-on-surface">
                        @foreach($letters as $letter)
                            <tr class="hover:bg-surface-container-low/60 transition-colors">
                                <td class="py-3 px-6 font-mono font-medium text-primary">
                                    {{ $letter['id'] }}
                                    <div class="text-[10px] text-outline font-sans mt-0.5">{{ $letter['number'] }}</div>
                                </td>
                                <td class="py-3 px-6 max-w-xs">
                                    <div class="font-bold text-on-surface truncate">{{ $letter['subject'] }}</div>
                                    <div class="text-[11px] text-on-surface-variant truncate mt-0.5">{{ $letter['sender'] }}</div>
                                </td>
                                <td class="py-3 px-6 text-on-surface-variant tabular-nums whitespace-nowrap">
                                    {{ $letter['received_at'] }}
                                </td>
                                <td class="py-3 px-6 text-on-surface-variant font-medium">
                                    {{ $letter['recipient'] }}
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap">
                                    <x-badge-status :status="$letter['status']" />
                                </td>
                                <td class="py-3 px-6 text-right whitespace-nowrap">
                                    <button
                                        type="button"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-surface-container hover:bg-primary-container hover:text-primary transition-colors"
                                        title="Segera hadir"
                                    >
                                        Disposisi
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>

<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Dashboard Tata Usaha
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Registrasi surat masuk dinas, penomoran berkas otomatis, dan pengarsipan digital SDN Jatipulo 07
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ Route::has('surat-masuk.index') ? route('surat-masuk.index') : '#' }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs shadow-sm hover:bg-primary-strong transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Kelola Surat Masuk</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI Metrik Tata Usaha -->
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

        <!-- 2. Grafik Tren & Kategori Surat Masuk (Chart.js via Vite) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{
            initCharts() {
                if (typeof Chart === 'undefined') return;

                const trendCtx = document.getElementById('trendChartTU')?.getContext('2d');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'bar',
                        data: {
                            labels: {{ json_encode($chartData['trend']['labels']) }},
                            datasets: [{
                                label: 'Surat Masuk',
                                data: {{ json_encode($chartData['trend']['data']) }},
                                backgroundColor: '#1B3A6B',
                                borderRadius: 6,
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
            <x-card class="lg:col-span-2" title="Tren Surat Masuk per Tanggal Penerimaan" subtitle="Aktivitas pencatatan naskah masuk oleh loket TU">
                <div class="h-64 w-full">
                    <canvas id="trendChartTU"></canvas>
                </div>
            </x-card>

            <x-card title="Distribusi Kategori Surat" subtitle="Proporsi kelompok perihal dokumen">
                <div class="h-64 w-full flex items-center justify-center">
                    <canvas id="categoryChartTU"></canvas>
                </div>
            </x-card>
        </div>

        <!-- 3. Tabel Surat Masuk Terbaru -->
        <x-card title="Surat Masuk Terbaru" subtitle="Daftar berkas naskah dinas yang baru dicatat dan diarsipkan">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">No. Agenda</th>
                            <th class="py-3 px-6">Pengirim & Nomor Surat</th>
                            <th class="py-3 px-6">Perihal</th>
                            <th class="py-3 px-6">Tanggal Masuk</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-on-surface">
                        @foreach($letters as $letter)
                            <tr class="hover:bg-surface-container-low/60 transition-colors">
                                <td class="py-3 px-6 font-mono font-bold text-primary whitespace-nowrap">
                                    {{ $letter['id'] }}
                                </td>
                                <td class="py-3 px-6">
                                    <div class="font-bold text-on-surface">{{ $letter['sender'] }}</div>
                                    <div class="text-[11px] text-on-surface-variant font-mono mt-0.5">{{ $letter['number'] }}</div>
                                </td>
                                <td class="py-3 px-6 max-w-sm">
                                    <p class="truncate font-medium text-on-surface">{{ $letter['subject'] }}</p>
                                    <span class="inline-block mt-1 text-[10px] px-1.5 py-0.2 rounded bg-surface-container text-on-surface-variant font-semibold">
                                        {{ $letter['category'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-on-surface-variant tabular-nums whitespace-nowrap">
                                    {{ $letter['received_at'] }}
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
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- 4. Repositori Arsip Digital (2x2 Grid) -->
        <x-card title="Repositori Arsip Digital" subtitle="Penyimpanan berkas surat dan dokumen dinas terverifikasi">
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

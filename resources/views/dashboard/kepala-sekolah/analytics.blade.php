<x-app-layout active="analytics">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">
                    <a href="{{ route('dashboard.kepala-sekolah') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-primary">Analitik Eksekutif</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-on-surface">
                    Analitik &amp; Ringkasan Persuratan
                </h1>
                <p class="text-sm text-on-surface-variant mt-1">
                    Visualisasi dinamika surat masuk, disposisi staf, dan beban kerja SDN Jatipulo 07 Jakarta.
                </p>
            </div>
            <div>
                <a href="{{ route('dashboard.kepala-sekolah') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-border-subtle bg-surface-lowest text-sm font-semibold text-on-surface hover:bg-surface-container shadow-xs transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Masuk</p>
                        <p class="text-3xl font-extrabold text-on-surface mt-1 tabular-nums">{{ $metrics['total_masuk'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Surat resmi teragenda</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-primary-container text-primary flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Keluar</p>
                        <p class="text-3xl font-extrabold text-on-surface mt-1 tabular-nums">{{ $metrics['total_keluar'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Naskah dinas keluar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-surface-container text-primary flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Disposisi Diterbitkan</p>
                        <p class="text-3xl font-extrabold text-on-surface mt-1 tabular-nums">{{ $metrics['total_disposisi'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Arahan &amp; delegasi tugas</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-secondary-container text-on-secondary-container flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Disposisi Tuntas</p>
                        <p class="text-3xl font-extrabold text-success mt-1 tabular-nums">{{ $metrics['selesai'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">Tindak lanjut selesai</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-success-container text-on-success-container flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Charts Grid -->
        <div
            class="grid grid-cols-1 lg:grid-cols-3 gap-6"
            x-data="{
                initCharts() {
                    if (typeof Chart === 'undefined') return;

                    // 1. Weekly Bar Chart
                    const weeklyEl = document.getElementById('analyticsWeeklyChart');
                    if (weeklyEl) {
                        new Chart(weeklyEl, {
                            type: 'bar',
                            data: {
                                labels: @js($chartData['trend']['labels'] ?? []),
                                datasets: [{
                                    label: 'Surat Masuk',
                                    data: @js($chartData['trend']['data'] ?? []),
                                    backgroundColor: '#1B3A6B',
                                    borderRadius: 6,
                                    borderSkipped: false,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { precision: 0 }
                                    }
                                }
                            }
                        });
                    }

                    // 2. Category Doughnut Chart
                    const catEl = document.getElementById('analyticsCategoryChart');
                    if (catEl) {
                        new Chart(catEl, {
                            type: 'doughnut',
                            data: {
                                labels: @js($chartData['categories']['labels'] ?? []),
                                datasets: [{
                                    data: @js($chartData['categories']['data'] ?? []),
                                    backgroundColor: ['#1B3A6B', '#C9A84C', '#0F2347', '#E8C97A', '#1E7B4F', '#64748B'],
                                    borderWidth: 2,
                                    borderColor: '#FFFFFF'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { boxWidth: 12, font: { size: 11 } }
                                    }
                                }
                            }
                        });
                    }
                }
            }"
            x-init="initCharts()"
        >
            <!-- Tren Volume Harian -->
            <x-card class="p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-on-surface">Tren Aktivitas Surat (7 Hari Terakhir)</h2>
                        <p class="text-xs text-on-surface-variant">Volume pencatatan surat masuk harian SDN Jatipulo 07</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-container text-primary">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                        Data Riil
                    </span>
                </div>
                <div class="h-64 relative">
                    <canvas id="analyticsWeeklyChart"></canvas>
                </div>
            </x-card>

            <!-- Distribusi Kategori -->
            <x-card class="p-6">
                <div class="mb-4">
                    <h2 class="text-base font-bold text-on-surface">Distribusi Kategori</h2>
                    <p class="text-xs text-on-surface-variant">Komposisi surat berdasarkan jenis naskah</p>
                </div>
                <div class="h-64 relative">
                    <canvas id="analyticsCategoryChart"></canvas>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

<x-app-layout active="analytics">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                    <a href="{{ route('dashboard.kepala-sekolah') }}" class="hover:text-primary-navy dark:hover:text-primary-gold transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-primary-navy dark:text-primary-gold">Analitik Eksekutif</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Analitik & Ringkasan Persuratan
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Visualisasi dinamika surat masuk, disposisi staf, dan beban kerja SDN Jatipulo 07 Jakarta.
                </p>
            </div>
            <div>
                <a href="{{ route('dashboard.kepala-sekolah') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Masuk</p>
                        <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['total_masuk'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Surat resmi teragenda</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-primary-navy/10 dark:bg-primary-gold/10 text-primary-navy dark:text-primary-gold flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">mark_email_unread</span>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Keluar</p>
                        <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['total_keluar'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Naskah dinas keluar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">outbox</span>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Disposisi Diterbitkan</p>
                        <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['total_disposisi'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Arahan & delegasi tugas</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">assignment</span>
                    </div>
                </div>
            </x-card>

            <x-card class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Disposisi Tuntas</p>
                        <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $metrics['selesai'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tindak lanjut selesai</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tren Volume Harian -->
            <x-card class="p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Tren Aktivitas Surat (7 Hari Terakhir)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Volume pencatatan surat masuk harian SDN Jatipulo 07</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-navy/10 dark:bg-primary-gold/15 text-primary-navy dark:text-primary-gold">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-navy dark:bg-primary-gold"></span>
                        Data Riil
                    </span>
                </div>
                <div class="h-64 relative">
                    <canvas id="analyticsWeeklyChart" data-chart='@json($chartData["weekly"])'></canvas>
                </div>
            </x-card>

            <!-- Distribusi Kategori -->
            <x-card class="p-6">
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Distribusi Kategori</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Komposisi surat berdasarkan jenis naskah</p>
                </div>
                <div class="h-64 relative">
                    <canvas id="analyticsCategoryChart" data-chart='@json($chartData["categories"])'></canvas>
                </div>
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Chart === 'undefined') return;

            // 1. Weekly Line/Bar Chart
            const weeklyEl = document.getElementById('analyticsWeeklyChart');
            if (weeklyEl) {
                const weeklyData = JSON.parse(weeklyEl.dataset.chart || '{}');
                new Chart(weeklyEl, {
                    type: 'bar',
                    data: {
                        labels: weeklyData.labels || [],
                        datasets: [{
                            label: 'Surat Masuk',
                            data: weeklyData.data || [],
                            backgroundColor: '#1E3A8A',
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
                const catData = JSON.parse(catEl.dataset.chart || '{}');
                new Chart(catEl, {
                    type: 'doughnut',
                    data: {
                        labels: catData.labels || [],
                        datasets: [{
                            data: catData.data || [],
                            backgroundColor: ['#1E3A8A', '#D97706', '#059669', '#6366F1', '#EC4899', '#64748B']
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
        });
    </script>
    @endpush
</x-app-layout>


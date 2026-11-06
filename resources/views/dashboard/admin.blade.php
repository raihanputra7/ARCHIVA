<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Dashboard Administrator
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Pemantauan integritas hak akses, audit trail, dan keamanan sistem ARCHIVA
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-container text-on-primary-container border border-primary/20">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>Admin Sistem</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI (2x2 Grid Legacy) -->
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 2. Repositori Arsip Basis Data (2x2 Grid di Legacy) -->
            <x-card title="Basis Data & Repositori Arsip" subtitle="Status repositori berkas persuratan terenkripsi">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-2">
                    @foreach($repositories as $repo)
                        <div class="p-3.5 rounded-xl border border-border-subtle bg-surface-container-low/40 hover:bg-surface-container-low transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-xs font-bold text-on-surface truncate">{{ $repo['title'] }}</h4>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-primary-container text-on-primary-container">{{ $repo['category'] }}</span>
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $repo['description'] }}
                            </p>
                            <div class="mt-3 pt-2 border-t border-border-subtle flex items-center justify-between text-[11px] text-outline">
                                <span>{{ $repo['files_count'] }} Dokumen</span>
                                <span>{{ $repo['updated_at'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            <!-- 3. Audit Trail & Riwayat Aktivitas Keamanan -->
            <x-card title="Log Audit & Keamanan Sistem" subtitle="Catatan kepatuhan dan histori aktivitas naskah dinas">
                <div class="divide-y divide-border-subtle -mx-2">
                    @foreach($activities as $act)
                        <div class="p-3 flex items-start gap-3 hover:bg-surface-container-low/50 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-container text-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-on-surface truncate">{{ $act['action'] }}</p>
                                    <span class="text-[10px] text-outline flex-shrink-0">{{ $act['time'] }}</span>
                                </div>
                                <p class="text-xs text-on-surface font-medium truncate mt-0.5">{{ $act['user'] }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate mt-0.5">{{ $act['detail'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

<x-app-layout active="dashboard">
    <x-slot name="header">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                Dashboard Administrator
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                Pemantauan akun pengguna ARCHIVA, aktivitas sesi, dan integritas sistem persuratan SDN Jatipulo 07
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">
        @php
            $pendingRegistrations = \App\Models\User::where('status', 'pending')->count();
        @endphp

        @if($pendingRegistrations > 0)
            <div class="p-4 rounded-2xl bg-warning-container text-on-warning-container border border-warning/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-xs sm:text-sm font-medium">
                    <span class="font-extrabold tabular-nums">{{ $pendingRegistrations }} permintaan pendaftaran akun baru</span> sedang menunggu tinjauan dan persetujuan Administrator.
                </div>
                <a
                    href="{{ route('dashboard.admin.registrations') }}"
                    class="inline-flex items-center justify-center px-3.5 py-2 rounded-lg bg-primary text-on-primary text-xs font-bold hover:bg-primary/90 transition-colors flex-shrink-0"
                >
                    Tinjau Pendaftaran
                </a>
            </div>
        @endif

        <!-- 1. Grid 4 Kartu KPI Sistem & Akun -->
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

        <!-- 2. Panel Pemantauan Akun Pengguna ARCHIVA -->
        <x-card
            title="Panel Pemantauan Akun ARCHIVA"
            subtitle="Daftar akun aktif per peran beserta email kedinasan dan status sesi untuk koordinasi & penanganan kendala pengguna"
        >
            <!-- Sebaran Peran + Filter Cepat -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-border-subtle">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 w-full lg:w-auto">
                    @foreach($roles as $roleCase)
                        <a
                            href="{{ route('dashboard.admin', ['role' => ($roleFilter === $roleCase->value ? null : $roleCase->value), 'q' => $search]) }}"
                            class="px-3 py-2 rounded-lg border text-left transition-colors {{ $roleFilter === $roleCase->value ? 'border-primary bg-primary-container text-primary' : 'border-border-subtle bg-surface-container-low/50 hover:bg-surface-container text-on-surface' }}"
                        >
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-on-surface-variant truncate">{{ $roleCase->label() }}</p>
                            <p class="text-base font-extrabold tabular-nums mt-0.5">{{ $monitoring['roleCounts'][$roleCase->value] ?? 0 }}</p>
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('dashboard.admin') }}" class="flex items-center gap-2 w-full lg:w-auto">
                    @if($roleFilter)
                        <input type="hidden" name="role" value="{{ $roleFilter }}">
                    @endif
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, unit..."
                        class="h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface placeholder:text-outline border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none w-full sm:w-56"
                    >
                    <button
                        type="submit"
                        class="h-10 px-3.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold border border-border-subtle transition-colors cursor-pointer"
                    >
                        Cari
                    </button>
                    @if($search || $roleFilter)
                        <a
                            href="{{ route('dashboard.admin') }}"
                            class="h-10 px-3 inline-flex items-center rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tabel Info Akun ARCHIVA -->
            <div class="overflow-x-auto -mx-6 -mb-6 mt-2" x-data="{ copiedId: null }">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Pengguna</th>
                            <th class="py-3 px-4">Email Kedinasan</th>
                            <th class="py-3 px-4">Peran &amp; Unit</th>
                            <th class="py-3 px-4">Status Sesi &amp; Verifikasi</th>
                            <th class="py-3 px-6 text-right">Dukungan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @forelse($monitoring['accounts'] as $account)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($account['name'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-on-surface truncate">{{ $account['name'] }}</p>
                                            <p class="text-[11px] text-outline">
                                                Terdaftar {{ $account['created_at']?->isoFormat('D MMM Y') ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-on-surface">{{ $account['email'] }}</span>
                                        <button
                                            type="button"
                                            @click="navigator.clipboard?.writeText('{{ $account['email'] }}'); copiedId = {{ $account['id'] }}; setTimeout(() => copiedId = null, 1500)"
                                            class="text-[11px] text-primary hover:underline cursor-pointer"
                                            title="Salin email"
                                        >
                                            <span x-show="copiedId !== {{ $account['id'] }}">Salin</span>
                                            <span x-show="copiedId === {{ $account['id'] }}" x-cloak class="text-success font-semibold">Tersalin</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-semibold text-on-surface">{{ $account['role_label'] }}</p>
                                    @if($account['unit'])
                                        <p class="text-[11px] text-on-surface-variant">Unit: {{ $account['unit'] }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $account['is_online'] ? 'bg-success' : 'bg-outline-variant' }}"></span>
                                        <span class="font-medium {{ $account['is_online'] ? 'text-success' : 'text-on-surface-variant' }}">
                                            {{ $account['is_online'] ? 'Sedang Aktif' : $account['last_active_human'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-outline mt-0.5">
                                        {{ $account['email_verified'] ? 'Email terverifikasi' : 'Belum verifikasi email' }}
                                        @if($account['ip_address'])
                                            &middot; IP {{ $account['ip_address'] }}
                                        @endif
                                    </p>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a
                                            href="mailto:{{ $account['email'] }}?subject={{ rawurlencode('[ARCHIVA] Dukungan Akun Pengguna') }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-subtle bg-surface-lowest hover:bg-surface-container text-on-surface font-semibold transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Hubungi</span>
                                        </a>
                                        <a
                                            href="{{ route('dashboard.admin.users', ['q' => $account['email']]) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-container text-primary hover:bg-primary/15 font-semibold transition-colors"
                                        >
                                            Kelola
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-on-surface-variant">
                                    Tidak ditemukan akun yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 3. Ringkasan Repositori Sistem -->
            <x-card title="Ringkasan Arsip Persuratan" subtitle="Statistik volume dokumen yang tersimpan di basis data ARCHIVA">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-2">
                    @foreach($repositories as $repo)
                        <div class="p-3.5 rounded-xl border border-border-subtle bg-surface-container-low/40">
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

            <!-- 4. Audit Trail & Riwayat Aktivitas Keamanan -->
            <x-card title="Log Aktivitas Disposisi Terbaru" subtitle="Catatan kepatuhan dan histori aktivitas persuratan terkini">
                <div class="divide-y divide-border-subtle -mx-2">
                    @forelse($activities as $act)
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
                    @empty
                        <div class="py-8 text-center text-xs text-on-surface-variant">
                            Belum ada aktivitas disposisi yang tercatat.
                        </div>
                    @endforelse
                </div>
                <div class="mt-3 pt-3 border-t border-border-subtle flex justify-end">
                    <a href="{{ route('dashboard.admin.audit') }}" class="text-xs font-semibold text-primary hover:underline">
                        Lihat Semua Log Audit &rarr;
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

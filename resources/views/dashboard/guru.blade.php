<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Dashboard Guru
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Penerimaan surat tugas dinas, tindak lanjut instruksi pimpinan, dan arsip kurikulum SDN Jatipulo 07
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-container text-on-primary-container border border-primary/20">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>Dewan Guru</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Grid 4 Kartu KPI Metrik Guru -->
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

        <!-- 2. Tabel Naskah Tugas & Disposisi untuk Guru -->
        <x-card title="Tugas Disposisi & Surat Masuk" subtitle="Daftar surat resmi dan edaran dinas yang ditujukan kepada Anda">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Agenda / Nomor Surat</th>
                            <th class="py-3 px-6">Perihal & Pengirim</th>
                            <th class="py-3 px-6">Batas / Tanggal</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-on-surface">
                        @forelse($letters as $letter)
                            <tr class="hover:bg-surface-container-low/60 transition-colors">
                                <td class="py-3 px-6 font-mono font-bold text-primary whitespace-nowrap">
                                    {{ $letter['id'] }}
                                    <div class="text-[10px] text-outline font-sans mt-0.5">{{ $letter['number'] }}</div>
                                </td>
                                <td class="py-3 px-6 max-w-md">
                                    <p class="font-bold text-on-surface truncate">{{ $letter['subject'] }}</p>
                                    <p class="text-[11px] text-on-surface-variant truncate mt-0.5">{{ $letter['sender'] }}</p>
                                </td>
                                <td class="py-3 px-6 text-on-surface-variant tabular-nums whitespace-nowrap">
                                    {{ $letter['date'] }}
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap">
                                    <x-badge-status :status="$letter['status']" />
                                </td>
                                <td class="py-3 px-6 text-right whitespace-nowrap">
                                    @if(!empty($letter['surat_id']))
                                        <a
                                            href="{{ route('surat-masuk.show', $letter['surat_id']) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-surface-container hover:bg-primary-container hover:text-primary transition-colors"
                                        >
                                            <span>Buka Dokumen</span>
                                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                        </a>
                                    @else
                                        <button
                                            type="button"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-surface-container hover:bg-primary-container hover:text-primary transition-colors"
                                            title="Segera hadir"
                                        >
                                            Buka Dokumen
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-on-surface-variant">
                                    Belum ada tugas disposisi baru yang ditujukan kepada Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- 3. Repositori Arsip Kurikulum -->
        <x-card title="Arsip Dokumen Kurikulum & Pembelajaran" subtitle="Koleksi silabus, Capaian Pembelajaran, dan panduan akademik">
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
                            <span>{{ $repo['files_count'] }} Berkas</span>
                            <span>{{ $repo['updated_at'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>
</x-app-layout>

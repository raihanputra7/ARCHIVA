<x-app-layout active="admin_audit">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Log Audit Sistem ARCHIVA
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Rekam jejak transisi status surat dan tindak lanjut oleh seluruh akun pengguna
                </p>
            </div>
            <div>
                <a
                    href="{{ route('dashboard.admin') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-subtle bg-surface-lowest text-xs font-semibold text-on-surface hover:bg-surface-container shadow-xs transition-colors"
                >
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-card title="Riwayat Perubahan Status &amp; Aktivitas" subtitle="Menampilkan jejak audit terbaru berdasarkan waktu kejadian">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Waktu</th>
                            <th class="py-3 px-4">No. Agenda</th>
                            <th class="py-3 px-4">Pelaku Aktivitas</th>
                            <th class="py-3 px-4">Transisi Status</th>
                            <th class="py-3 px-6">Catatan Audit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="py-3.5 px-6 whitespace-nowrap text-on-surface-variant tabular-nums">
                                    {{ $log->created_at?->isoFormat('D MMM Y, HH:mm') ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-on-surface tabular-nums">
                                    {{ $log->suratMasuk?->nomor_agenda ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-on-surface">{{ $log->user?->name ?? 'Sistem' }}</p>
                                    @if($log->user?->email)
                                        <p class="text-[11px] text-outline">{{ $log->user->email }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-on-surface-variant">{{ $log->dari_status ?? 'Awal' }}</span>
                                    <span class="mx-1 text-outline">&rarr;</span>
                                    <span class="font-bold text-primary">{{ $log->ke_status }}</span>
                                </td>
                                <td class="py-3.5 px-6 text-on-surface-variant">
                                    {{ $log->catatan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-on-surface-variant">
                                    Belum ada catatan log audit yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($auditLogs->hasPages())
                <div class="mt-6 pt-4 border-t border-border-subtle">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </x-card>

        @if(isset($adminAuditLogs))
            <x-card title="Log Audit Tata Kelola Akun" subtitle="Catatan persetujuan, penolakan, perubahan peran, dan penonaktifan akun pengguna">
                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                                <th class="py-3 px-6">Waktu</th>
                                <th class="py-3 px-4">Administrator</th>
                                <th class="py-3 px-4">Pengguna Target</th>
                                <th class="py-3 px-4">Aksi</th>
                                <th class="py-3 px-6">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle">
                            @forelse($adminAuditLogs as $adminLog)
                                <tr class="hover:bg-surface-container-low/40 transition-colors">
                                    <td class="py-3.5 px-6 whitespace-nowrap text-on-surface-variant tabular-nums">
                                        {{ $adminLog->created_at?->isoFormat('D MMM Y, HH:mm') ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-on-surface">
                                        {{ $adminLog->actor?->name ?? 'Sistem' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-on-surface">{{ $adminLog->targetUser?->name ?? '-' }}</p>
                                        @if($adminLog->targetUser?->email)
                                            <p class="text-[11px] text-outline">{{ $adminLog->targetUser->email }}</p>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-primary">
                                        {{ ucwords(str_replace('_', ' ', $adminLog->action)) }}
                                    </td>
                                    <td class="py-3.5 px-6 text-on-surface-variant">
                                        {{ $adminLog->details ? json_encode($adminLog->details, JSON_UNESCAPED_UNICODE) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-6 text-center text-on-surface-variant">
                                        Belum ada log audit tata kelola akun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($adminAuditLogs->hasPages())
                    <div class="mt-6 pt-4 border-t border-border-subtle">
                        {{ $adminAuditLogs->links() }}
                    </div>
                @endif
            </x-card>
        @endif
    </div>
</x-app-layout>


<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-on-surface leading-tight">Dashboard Kepala Sekolah</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Pemantauan persuratan dinas, disposisi, dan persetujuan</p>
            </div>
            <x-badge-status status="Didisposisikan" />
        </div>
    </x-slot>

    <x-card title="Selamat Datang, {{ auth()->user()->name }}" subtitle="Panel Kepemimpinan SDN Jatipulo 07">
        <p class="text-sm text-on-surface-variant leading-relaxed">
            Anda dapat memantau pergerakan naskah dinas, memberikan instruksi disposisi kepada staf dan dewan guru, serta menyetujui surat keluar.
        </p>
    </x-card>
</x-app-layout>


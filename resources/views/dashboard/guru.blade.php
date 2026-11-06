<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-on-surface leading-tight">Dashboard Guru</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Penerimaan surat tugas, disposisi pimpinan, dan arsip kurikulum</p>
            </div>
            <x-badge-status status="Diproses" />
        </div>
    </x-slot>

    <x-card title="Selamat Datang, {{ auth()->user()->name }}" subtitle="Panel Dewan Guru SDN Jatipulo 07">
        <p class="text-sm text-on-surface-variant leading-relaxed">
            Anda dapat melihat surat dan disposisi yang ditujukan kepada Anda, menindaklanjuti instruksi kedinasan, dan mengunggah laporan pelaksanaan tugas.
        </p>
    </x-card>
</x-app-layout>


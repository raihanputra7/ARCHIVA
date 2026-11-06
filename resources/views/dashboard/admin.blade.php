<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-on-surface leading-tight">Dashboard Administrator</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Pengelolaan hak akses dan keamanan sistem ARCHIVA</p>
            </div>
            <x-badge-status status="Selesai" />
        </div>
    </x-slot>

    <x-card title="Selamat Datang, {{ auth()->user()->name }}" subtitle="Panel Administrator Sistem SDN Jatipulo 07">
        <p class="text-sm text-on-surface-variant leading-relaxed">
            Anda memiliki wewenang penuh dalam manajemen pengguna, hak akses peran, serta pemantauan audit trail persuratan sekolah.
        </p>
    </x-card>
</x-app-layout>


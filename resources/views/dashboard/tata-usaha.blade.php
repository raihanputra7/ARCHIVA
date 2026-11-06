<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-on-surface leading-tight">Dashboard Tata Usaha</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Registrasi surat masuk, penomoran otomatis, dan pengarsipan</p>
            </div>
            <x-badge-status status="Diterima" />
        </div>
    </x-slot>

    <x-card title="Selamat Datang, {{ auth()->user()->name }}" subtitle="Panel Pelayanan Administrasi & Persuratan">
        <p class="text-sm text-on-surface-variant leading-relaxed">
            Kelola registrasi surat masuk, pemindaian naskah berkas, distribusi elektronik ke pimpinan, serta penyusunan draf surat keluar.
        </p>
    </x-card>
</x-app-layout>


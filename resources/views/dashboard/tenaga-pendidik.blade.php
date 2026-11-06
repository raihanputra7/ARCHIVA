<x-app-layout active="dashboard">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-on-surface leading-tight">Dashboard Tenaga Pendidik & Kependidikan</h2>
                <p class="text-xs text-on-surface-variant mt-0.5">Penerimaan informasi kedinasan, sarpras, dan tugas operasional</p>
            </div>
            <x-badge-status status="Diproses" />
        </div>
    </x-slot>

    <x-card title="Selamat Datang, {{ auth()->user()->name }}" subtitle="Panel Tenaga Pendidik & Penunjang Sekolah">
        <p class="text-sm text-on-surface-variant leading-relaxed">
            Anda dapat melihat pengumuman kedinasan, surat tugas penunjang, serta instruksi kerja operasional yang ditujukan langsung kepada Anda.
        </p>
    </x-card>
</x-app-layout>


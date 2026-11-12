<x-app-layout active="surat-keluar">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                    <a href="{{ route('surat-keluar.index') }}" class="hover:text-primary-navy dark:hover:text-primary-gold transition-colors">Surat Keluar</a>
                    <span>/</span>
                    <span class="text-primary-navy dark:text-primary-gold font-mono">{{ $suratKeluar->nomor_agenda }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Detail Naskah Surat Keluar
                    </h1>
                    <x-badge-status :status="$suratKeluar->status" />
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali</span>
                </a>

                @if($suratKeluar->file_path)
                    <a href="{{ route('surat-keluar.download', $suratKeluar) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold shadow-sm hover:opacity-90 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Unduh Berkas</span>
                    </a>
                @endif

                @can('approve', $suratKeluar)
                    @if($suratKeluar->status === 'Draf')
                        <form action="{{ route('surat-keluar.approve', $suratKeluar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui naskah surat keluar ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                </svg>
                                <span>Setujui Naskah Keluar</span>
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>

        <!-- Alert Success -->
        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Metadata Utama -->
            <div class="lg:col-span-2 space-y-6">
                @if($suratKeluar->parentSuratMasuk)
                    <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-xs">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Naskah Balasan Terkait</p>
                                <p class="text-sm font-bold mt-0.5">Surat Masuk: {{ $suratKeluar->parentSuratMasuk->nomor_agenda }}</p>
                                <p class="text-xs text-blue-800 dark:text-blue-300 mt-0.5">{{ $suratKeluar->parentSuratMasuk->perihal }} (Pengirim: {{ $suratKeluar->parentSuratMasuk->pengirim }})</p>
                            </div>
                        </div>
                        <a href="{{ route('surat-masuk.show', $suratKeluar->parentSuratMasuk) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors whitespace-nowrap self-start sm:self-center shadow-xs">
                            <span>Buka Surat Masuk</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>
                    </div>
                @endif

                <x-card class="p-6">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                        Informasi Naskah Surat
                    </h2>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomor Agenda (Sistem)</dt>
                            <dd class="mt-1 font-mono font-bold text-primary-navy dark:text-primary-gold text-base">{{ $suratKeluar->nomor_agenda }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomor Surat Resmi</dt>
                            <dd class="mt-1 font-mono font-bold text-slate-900 dark:text-white text-base">
                                {{ $suratKeluar->nomor_surat ?? '(Belum diterbitkan)' }}
                            </dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Klasifikasi Arsip (Kepgub DKI 1598/2021)</dt>
                            <dd class="mt-1 font-medium text-slate-900 dark:text-white flex flex-wrap items-center gap-2">
                                @if($suratKeluar->kode_klasifikasi)
                                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-primary-navy dark:text-primary-gold border border-slate-200 dark:border-slate-700">
                                        {{ $suratKeluar->kode_klasifikasi }}
                                    </span>
                                    <span>{{ $suratKeluar->nama_klasifikasi ?? 'Klasifikasi Persuratan Resmi' }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tujuan / Penerima</dt>
                            <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $suratKeluar->tujuan }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal Surat</dt>
                            <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $suratKeluar->tanggal_surat->isoFormat('dddd, D MMMM Y') }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kategori Surat</dt>
                            <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $suratKeluar->kategori }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sifat Penyampaian</dt>
                            <dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $suratKeluar->sifat }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Perihal</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white font-medium leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                {{ $suratKeluar->perihal }}
                            </dd>
                        </div>

                        @if($suratKeluar->ringkasan)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ringkasan / Catatan</dt>
                                <dd class="mt-1 text-slate-700 dark:text-slate-300 text-xs leading-relaxed bg-slate-50 dark:bg-slate-800/40 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                    {{ $suratKeluar->ringkasan }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </x-card>
            </div>

            <!-- Kolom Berkas & Pengesahan -->
            <div class="space-y-6">
                <!-- Status & Pengesahan -->
                <x-card class="p-6">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                        Status & Otorisasi
                    </h2>

                    <div class="space-y-4 text-xs">
                        <div>
                            <p class="font-semibold text-slate-500 dark:text-slate-400">Pencatat / Pembuat Draf</p>
                            <p class="font-bold text-slate-900 dark:text-white mt-0.5">{{ $suratKeluar->creator->name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $suratKeluar->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <p class="font-semibold text-slate-500 dark:text-slate-400">Persetujuan Kepala Sekolah</p>
                            @if($suratKeluar->approved_by)
                                <div class="mt-1 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800">
                                    <div class="flex items-center gap-1.5 text-emerald-800 dark:text-emerald-200 font-bold">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span>Telah Disetujui</span>
                                    </div>
                                    <p class="text-slate-700 dark:text-slate-300 font-medium mt-1">{{ $suratKeluar->approver?->name }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $suratKeluar->approved_at?->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                                </div>
                            @else
                                <div class="mt-1 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300">
                                    <div class="flex items-center gap-1.5 font-bold">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span>Menunggu Persetujuan</span>
                                    </div>
                                    <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5">Naskah belum diverifikasi oleh pimpinan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>

                <!-- Lampiran Digital -->
                <x-card class="p-6">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                        Lampiran Berkas
                    </h2>

                    @if($suratKeluar->file_path)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <svg class="w-6 h-6 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <div class="flex-1 min-w-0 text-xs">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $suratKeluar->file_name ?? 'Berkas Lampiran' }}</p>
                                <p class="text-slate-400 text-[11px] mt-0.5">
                                    {{ $suratKeluar->file_size ? number_format($suratKeluar->file_size / 1024, 1) . ' KB' : 'PDF/Image' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('surat-keluar.download', $suratKeluar) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary-navy dark:bg-primary-gold text-white dark:text-slate-900 text-xs font-bold shadow-md hover:opacity-95 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>Unduh Lampiran Resmi</span>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                            </svg>
                            <p class="text-xs">Tidak ada berkas lampiran yang diunggah.</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>


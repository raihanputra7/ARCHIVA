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
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali</span>
                </a>

                @if($suratKeluar->file_path)
                    <a href="{{ route('surat-keluar.download', $suratKeluar) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs font-bold shadow-sm hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span>Unduh Berkas</span>
                    </a>
                @endif

                @can('approve', $suratKeluar)
                    @if($suratKeluar->status === 'Draf')
                        <form action="{{ route('surat-keluar.approve', $suratKeluar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui naskah surat keluar ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-all">
                                <span class="material-symbols-outlined text-[16px]">verified</span>
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
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Metadata Utama -->
            <div class="lg:col-span-2 space-y-6">
                @if($suratKeluar->parentSuratMasuk)
                    <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-xs">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400 mt-0.5">reply</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Naskah Balasan Terkait</p>
                                <p class="text-sm font-bold mt-0.5">Surat Masuk: {{ $suratKeluar->parentSuratMasuk->nomor_agenda }}</p>
                                <p class="text-xs text-blue-800 dark:text-blue-300 mt-0.5">{{ $suratKeluar->parentSuratMasuk->perihal }} (Pengirim: {{ $suratKeluar->parentSuratMasuk->pengirim }})</p>
                            </div>
                        </div>
                        <a href="{{ route('surat-masuk.show', $suratKeluar->parentSuratMasuk) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors whitespace-nowrap self-start sm:self-center shadow-xs">
                            <span>Buka Surat Masuk</span>
                            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
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
                            <dd class="mt-1 font-mono font-medium text-slate-900 dark:text-white">
                                {{ $suratKeluar->nomor_surat ?? '(Belum diterbitkan)' }}
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
                                        <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                                        <span>Telah Disetujui</span>
                                    </div>
                                    <p class="text-slate-700 dark:text-slate-300 font-medium mt-1">{{ $suratKeluar->approver?->name }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $suratKeluar->approved_at?->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                                </div>
                            @else
                                <div class="mt-1 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300">
                                    <div class="flex items-center gap-1.5 font-bold">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
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
                            <span class="material-symbols-outlined text-2xl text-primary-navy dark:text-primary-gold mt-0.5">description</span>
                            <div class="flex-1 min-w-0 text-xs">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $suratKeluar->file_name ?? 'Berkas Lampiran' }}</p>
                                <p class="text-slate-400 text-[11px] mt-0.5">
                                    {{ $suratKeluar->file_size ? number_format($suratKeluar->file_size / 1024, 1) . ' KB' : 'PDF/Image' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('surat-keluar.download', $suratKeluar) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-primary-navy dark:bg-primary-gold text-white dark:text-slate-900 text-xs font-bold shadow-md hover:opacity-95 transition-all">
                                <span class="material-symbols-outlined text-[16px]">download</span>
                                <span>Unduh Lampiran Resmi</span>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-1 block text-slate-300 dark:text-slate-600">attachment</span>
                            <p class="text-xs">Tidak ada berkas lampiran yang diunggah.</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>


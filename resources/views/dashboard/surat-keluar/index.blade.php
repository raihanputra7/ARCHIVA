<x-app-layout active="surat-keluar">
    <div class="space-y-6" x-data="{ openCreateModal: false }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary-navy dark:hover:text-primary-gold transition-colors">Dashboard</a>
                    <span>/</span>
                    <span class="text-primary-navy dark:text-primary-gold">Surat Keluar</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Agenda Surat Keluar
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Pencatatan naskah dinas keluar, persetujuan pimpinan, dan pengarsipan nomor resmi.
                </p>
            </div>

            <div class="flex items-center gap-3">
                @can('create', \App\Models\SuratKeluar::class)
                    <button type="button" @click="openCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-navy dark:bg-primary-gold text-white dark:text-slate-900 text-sm font-bold shadow-md hover:opacity-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>Catat Surat Keluar</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- Alert Success / Error -->
        @if (session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 4 Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Naskah</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $metrics['total'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Surat keluar teragenda</p>
            </x-card>
            <x-card class="p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Draf / Konsep</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $metrics['draf'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Menunggu persetujuan</p>
            </x-card>
            <x-card class="p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Disetujui</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $metrics['disetujui'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Siap dikirim / diteken</p>
            </x-card>
            <x-card class="p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Terkirim</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $metrics['terkirim'] }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Telah didistribusikan</p>
            </x-card>
        </div>

        <!-- Filter & Search Bar -->
        <x-card class="p-4">
            <form method="GET" action="{{ route('surat-keluar.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor agenda, perihal, atau tujuan surat..." class="w-full pl-9 pr-4 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select name="status" class="py-2 px-3 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                        <option value="">Semua Status</option>
                        <option value="Draf" {{ request('status') === 'Draf' ? 'selected' : '' }}>Draf</option>
                        <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Terkirim" {{ request('status') === 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Saring
                    </button>

                    @if(request()->hasAny(['q', 'status']))
                        <a href="{{ route('surat-keluar.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Surat Keluar -->
        <x-card class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4">Agenda / Nomor</th>
                            <th class="py-3 px-4">Tujuan</th>
                            <th class="py-3 px-4">Perihal</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Kategori & Sifat</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Berkas</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
                        @forelse ($suratKeluar as $item)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-primary-navy dark:text-primary-gold font-mono text-xs">
                                        {{ $item->nomor_agenda }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                        {{ $item->nomor_surat ?? '(Nomor belum terbit)' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white max-w-[200px] truncate">
                                    {{ $item->tujuan }}
                                </td>
                                <td class="py-3 px-4 text-slate-700 dark:text-slate-300 max-w-[260px]">
                                    <div class="line-clamp-2">{{ $item->perihal }}</div>
                                </td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400 text-xs whitespace-nowrap">
                                    {{ $item->tanggal_surat->isoFormat('D MMM Y') }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $item->kategori }}</div>
                                    <span class="inline-block text-[10px] px-1.5 py-0.5 rounded font-medium mt-0.5
                                        {{ $item->sifat === 'Kilat' ? 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300' :
                                           ($item->sifat === 'Segera' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300') }}">
                                        {{ $item->sifat }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <x-badge-status :status="$item->status" />
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->file_path)
                                        <a href="{{ route('surat-keluar.download', $item) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-primary-navy dark:text-primary-gold hover:opacity-80" title="Unduh Berkas Lampiran">
                                            <span class="material-symbols-outlined text-[18px]">attachment</span>
                                        </a>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('surat-keluar.show', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                        <span>Rincian</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300 dark:text-slate-600 block">inventory_2</span>
                                    <p class="font-semibold">Belum ada agenda surat keluar yang tercatat.</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Surat Keluar" untuk mendaftarkan naskah baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suratKeluar->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $suratKeluar->links() }}
                </div>
            @endif
        </x-card>

        <!-- Modal Tambah Surat Keluar -->
        @can('create', \App\Models\SuratKeluar::class)
            <div x-show="openCreateModal"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
                 @keydown.escape.window="openCreateModal = false">
                <div class="relative w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 sm:p-8"
                     @click.away="openCreateModal = false">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Catat Surat Keluar Baru</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nomor agenda ARC/SK akan di-generate otomatis oleh sistem.</p>
                        </div>
                        <button type="button" @click="openCreateModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Surat Resmi (Opsional)</label>
                                <input type="text" name="nomor_surat" placeholder="Misal: 421.2/088/SDN07/2026" class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_surat" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pihak / Instansi Tujuan <span class="text-red-500">*</span></label>
                            <input type="text" name="tujuan" required placeholder="Misal: Suku Dinas Pendidikan Wilayah II Jakarta Barat" class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Perihal Surat <span class="text-red-500">*</span></label>
                            <textarea name="perihal" rows="2" required placeholder="Tuliskan subjek atau perihal naskah surat dinas..." class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Naskah <span class="text-red-500">*</span></label>
                                <select name="kategori" required class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                                    <option value="Dinas">Kedinasan Resmi</option>
                                    <option value="Undangan">Undangan Rapat / Kegiatan</option>
                                    <option value="SK Tugas">Surat Tugas / Keputusan</option>
                                    <option value="Pemberitahuan">Surat Pemberitahuan Wali Murid</option>
                                    <option value="Laporan">Laporan Pertanggungjawaban</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Sifat Penyampaian <span class="text-red-500">*</span></label>
                                <select name="sifat" required class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                                    <option value="Biasa">Biasa</option>
                                    <option value="Segera">Segera</option>
                                    <option value="Kilat">Kilat</option>
                                    <option value="Rahasia">Rahasia</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ringkasan / Catatan Isi (Opsional)</label>
                            <textarea name="ringkasan" rows="2" placeholder="Catatan singkat isi surat untuk arsip..." class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Lampiran Naskah Digital (PDF / Gambar, Maks. 5MB)</label>
                            <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-navy/10 file:text-primary-navy dark:file:bg-primary-gold/10 dark:file:text-primary-gold hover:file:opacity-80">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="openCreateModal = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-primary-navy dark:bg-primary-gold text-white dark:text-slate-900 text-sm font-bold shadow-md hover:opacity-95 transition-all">
                                Simpan Surat Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    </div>
</x-app-layout>


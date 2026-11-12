<x-app-layout active="surat-keluar">
    <div class="space-y-6" x-data="{
        openCreateModal: {{ !empty($parentSuratMasuk) || $errors->any() ? 'true' : 'false' }},
        nextUrut: {{ (int) $nextNomorUrut }},
        kodeKlasifikasi: @js(old('kode_klasifikasi', $defaultKlasifikasi)),
        nomorSurat: @js(old('nomor_surat', $nextNomorUrut . '/' . $defaultKlasifikasi)),
        manualNomor: {{ old('nomor_surat') ? 'true' : 'false' }},
        klasifikasiMap: @js($klasifikasiMap),
        updateAutoNomor() {
            if (!this.manualNomor) {
                this.nomorSurat = `${this.nextUrut}/${this.kodeKlasifikasi}`;
            }
        },
        resetAutoNomor() {
            this.manualNomor = false;
            this.nomorSurat = `${this.nextUrut}/${this.kodeKlasifikasi}`;
        }
    }">
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
                    <button type="button" @click="openCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold shadow-sm hover:opacity-95 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Catat Surat Keluar</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- Alert Success / Error -->
        @if (session('success'))
            <div class="p-4 rounded-xl bg-success-container border border-success/30 text-on-success-container text-sm flex items-center gap-3" role="status">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
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
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor surat (mis. PK.01.01), agenda, perihal, atau tujuan..." class="w-full pl-9 pr-4 py-2 rounded-xl text-sm border border-outline-variant bg-surface-lowest text-on-surface placeholder-outline focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <select name="klasifikasi" class="py-2 px-3 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                        <option value="">Semua Klasifikasi</option>
                        @foreach($klasifikasiGroups as $groupCode => $group)
                            <option value="{{ $groupCode }}" {{ request('klasifikasi') === $groupCode ? 'selected' : '' }}>{{ $group['label'] }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="py-2 px-3 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                        <option value="">Semua Status</option>
                        <option value="Draf" {{ request('status') === 'Draf' ? 'selected' : '' }}>Draf</option>
                        <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Terkirim" {{ request('status') === 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Saring
                    </button>

                    @if(request()->hasAny(['q', 'status', 'klasifikasi']))
                        <a href="{{ route('surat-keluar.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel / Daftar Surat Keluar -->
        @php
            $daftarKeluar = $suratKeluar->getCollection()->map(fn ($surat) => [
                'surat' => $surat,
                'showUrl' => route('surat-keluar.show', $surat),
                'downloadUrl' => $surat->file_path ? route('surat-keluar.download', $surat) : null,
                'tanggalLabel' => $surat->tanggal_surat->isoFormat('D MMM Y'),
                'sifatClass' => match ($surat->sifat) {
                    'Kilat' => 'bg-error-container text-on-error-container',
                    'Segera' => 'bg-secondary-container text-on-secondary-container',
                    default => 'bg-surface-container text-on-surface-variant',
                },
            ]);
        @endphp

        <x-card title="Daftar Agenda Surat Keluar" subtitle="Total {{ $suratKeluar->total() }} naskah dinas keluar tercatat">
            @if($daftarKeluar->isEmpty())
                <div class="py-12 px-4 text-center text-on-surface-variant">
                    <div class="max-w-sm mx-auto space-y-2">
                        <svg class="w-10 h-10 mx-auto text-outline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                        </svg>
                        <p class="font-bold text-base text-on-surface">Belum ada agenda surat keluar yang tercatat</p>
                        <p class="text-xs leading-5">Gunakan tombol "Catat Surat Keluar" untuk mendaftarkan naskah baru.</p>
                    </div>
                </div>
            @else
                <!-- Tampilan Desktop (>= 1280px): Tabel semantik tanpa scroll horizontal -->
                <div class="hidden xl:block -mx-6 {{ $suratKeluar->hasPages() ? '' : '-mb-6 rounded-b-2xl' }} overflow-hidden">
                    <table class="w-full table-fixed text-left border-collapse">
                        <caption class="sr-only">Daftar Agenda Surat Keluar</caption>
                        <colgroup>
                            <col style="width: 18%">
                            <col style="width: 29%">
                            <col style="width: 21%">
                            <col style="width: 11%">
                            <col style="width: 14%">
                            <col style="width: 7%">
                        </colgroup>
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant text-xs leading-4 font-semibold uppercase tracking-[0.02em] border-y border-border-subtle">
                                <th scope="col" class="py-3 pl-6 pr-3">NOMOR</th>
                                <th scope="col" class="py-3 px-3">PERIHAL</th>
                                <th scope="col" class="py-3 px-3">TUJUAN</th>
                                <th scope="col" class="py-3 px-2.5">TANGGAL</th>
                                <th scope="col" class="py-3 px-2">STATUS</th>
                                <th scope="col" class="py-3 pl-1 pr-4 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-on-surface">
                            @foreach($daftarKeluar as $item)
                                <x-surat-keluar-row :item="$item" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tampilan Mobile & Tablet (< 1280px): Daftar kartu -->
                <div class="xl:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($daftarKeluar as $item)
                        <x-surat-keluar-card :item="$item" />
                    @endforeach
                </div>
            @endif

            @if ($suratKeluar->hasPages())
                <div class="pt-4 mt-4 xl:mt-0 border-t border-border-subtle">
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
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Nomor surat resmi mengikuti klasifikasi Kepgub DKI 1598/2021 &amp; nomor agenda di-generate otomatis.</p>
                        </div>
                        <button type="button" @click="openCreateModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" aria-label="Tutup modal">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-xs">
                            <p class="font-bold mb-1">Terdapat kesalahan pengisian:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @if(!empty($parentSuratMasuk))
                            <input type="hidden" name="parent_surat_masuk_id" value="{{ $parentSuratMasuk->id }}">
                            <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-xs flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>
                                <div>
                                    <p class="font-bold">Balasan untuk Surat Masuk: {{ $parentSuratMasuk->nomor_agenda }}</p>
                                    <p class="text-[11px] text-blue-700 dark:text-blue-300 mt-0.5">{{ $parentSuratMasuk->perihal }} (Pengirim: {{ $parentSuratMasuk->pengirim }})</p>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Klasifikasi Arsip (Kepgub DKI No. 1598/2021) <span class="text-red-500">*</span>
                            </label>
                            <select name="kode_klasifikasi" x-model="kodeKlasifikasi" @change="updateAutoNomor()" required class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                                @foreach($klasifikasiGroups as $group)
                                    <optgroup label="{{ $group['label'] }}">
                                        @foreach($group['items'] as $code => $item)
                                            <option value="{{ $code }}">{{ $code }} — {{ $item['nama'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400" x-text="klasifikasiMap[kodeKlasifikasi]?.keterangan || ''"></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Nomor Surat Resmi</label>
                                    <button type="button" x-show="manualNomor" @click="resetAutoNomor()" class="text-[11px] font-semibold text-primary-navy dark:text-primary-gold hover:underline">
                                        Reset Otomatis
                                    </button>
                                </div>
                                <input type="text" name="nomor_surat" x-model="nomorSurat" @input="manualNomor = true" value="{{ old('nomor_surat', $nextNomorUrut . '/' . $defaultKlasifikasi) }}" placeholder="Misal: {{ $nextNomorUrut }}/{{ $defaultKlasifikasi }}" class="w-full px-3.5 py-2 rounded-xl text-sm font-mono border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                                <p class="mt-1 text-[11px] text-slate-400">Otomatis format <span class="font-mono font-semibold text-slate-600 dark:text-slate-300" x-text="`${nextUrut}/${kodeKlasifikasi}`">{{ $nextNomorUrut }}/{{ $defaultKlasifikasi }}</span> (kosongkan untuk isi otomatis).</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pihak / Instansi Tujuan <span class="text-red-500">*</span></label>
                            <input type="text" name="tujuan" value="{{ old('tujuan', $parentSuratMasuk?->pengirim) }}" required placeholder="Misal: Suku Dinas Pendidikan Wilayah II Jakarta Barat" class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Perihal Surat <span class="text-red-500">*</span></label>
                            <textarea name="perihal" rows="2" required placeholder="Tuliskan subjek atau perihal naskah surat dinas..." class="w-full px-3.5 py-2 rounded-xl text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-navy dark:focus:ring-primary-gold">{{ old('perihal', !empty($parentSuratMasuk) ? 'Tanggapan atas: ' . $parentSuratMasuk->perihal : '') }}</textarea>
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
                            <label for="berkas_keluar" class="block text-xs font-semibold text-on-surface mb-1">Lampiran Naskah Digital (PDF / Gambar, Maks. 5MB)</label>
                            <x-upload-progress id="berkas_keluar" name="berkas" />
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


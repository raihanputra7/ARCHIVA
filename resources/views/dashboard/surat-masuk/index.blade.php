<x-app-layout active="surat_masuk">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Manajemen Surat Masuk
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Pencatatan naskah dinas, registrasi agenda, pemindaian berkas, dan distribusi disposisi SDN Jatipulo 07
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                @can('create', App\Models\SuratMasuk::class)
                    <button
                        type="button"
                        @click="$dispatch('open-create-modal')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-on-primary font-semibold text-xs shadow-sm hover:bg-primary-strong transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Surat Masuk</span>
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{
        createModal: false,
        disposisiModal: false,
        selectedSurat: null,
        openDisposisi(surat) {
            this.selectedSurat = surat;
            this.disposisiModal = true;
        }
    }" @open-create-modal.window="createModal = true">

        <!-- 1. Empat Kartu Metrik Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Surat Masuk</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-on-surface tracking-tight tabular-nums">{{ $stats['total'] }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Berkas</span>
                </div>
                <p class="text-[11px] text-outline mt-1">Terarsip di sistem ARCHIVA</p>
            </x-card>

            <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Surat Bulan Ini</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-primary tracking-tight tabular-nums">{{ $stats['bulan_ini'] }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Berkas</span>
                </div>
                <p class="text-[11px] text-outline mt-1">Periode {{ Carbon\Carbon::now()->isoFormat('MMMM Y') }}</p>
            </x-card>

            <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Menunggu Disposisi</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-gold-text tracking-tight tabular-nums">{{ $stats['perlu_disposisi'] }}</span>
                    <span class="text-xs font-medium text-on-surface-variant">Naskah</span>
                </div>
                <p class="text-[11px] text-outline mt-1">Perlu arahan pimpinan</p>
            </x-card>

            <x-card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Arsip Terakhir</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-xl font-bold font-mono text-on-surface truncate">{{ $stats['arsip_terakhir'] }}</span>
                </div>
                <p class="text-[11px] text-outline mt-1">Nomor urut otomatis</p>
            </x-card>
        </div>

        <!-- 2. Filter & Form Pencarian -->
        <x-card>
            <form method="GET" action="{{ route('surat-masuk.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <!-- Search input -->
                <div class="sm:col-span-5">
                    <label for="q" class="block text-xs font-bold text-on-surface mb-1">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-outline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" stroke-width="2"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"></line>
                            </svg>
                        </div>
                        <input
                            type="search"
                            id="q"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari nomor agenda, nomor surat, pengirim..."
                            class="w-full h-10 pl-9 pr-3 rounded-lg text-xs bg-surface-lowest border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                        >
                    </div>
                </div>

                <!-- Kategori select -->
                <div class="sm:col-span-3">
                    <label for="kategori" class="block text-xs font-bold text-on-surface mb-1">Kategori</label>
                    <select
                        id="kategori"
                        name="kategori"
                        class="w-full h-10 px-3 rounded-lg text-xs bg-surface-lowest border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                    >
                        <option value="semua">Semua Kategori</option>
                        @foreach(['Dinas', 'Undangan', 'Kurikulum', 'Keuangan', 'Kesehatan', 'SK Tugas'] as $cat)
                            <option value="{{ $cat }}" {{ request('kategori') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status select -->
                <div class="sm:col-span-2">
                    <label for="status" class="block text-xs font-bold text-on-surface mb-1">Status</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full h-10 px-3 rounded-lg text-xs bg-surface-lowest border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                    >
                        <option value="semua">Semua Status</option>
                        @foreach(['Diterima', 'Didisposisikan', 'Diproses', 'Selesai'] as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Filter -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <x-button type="submit" variant="primary" size="md" class="w-full">
                        Saring
                    </x-button>
                    @if(request()->hasAny(['q', 'kategori', 'status']))
                        <a href="{{ route('surat-masuk.index') }}" class="px-2.5 py-2.5 rounded-lg border border-border-subtle bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- 3. Tabel Surat Masuk -->
        <x-card title="Daftar Naskah Dinas Masuk" subtitle="Total {{ $suratList->total() }} naskah dinas tercatat">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Agenda / Nomor Asli</th>
                            <th class="py-3 px-6">Pengirim</th>
                            <th class="py-3 px-6">Perihal & Kategori</th>
                            <th class="py-3 px-6">Tanggal Masuk</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-on-surface">
                        @forelse($suratList as $surat)
                            <tr class="hover:bg-surface-container-low/60 transition-colors">
                                <td class="py-3.5 px-6 font-mono whitespace-nowrap">
                                    <a href="{{ route('surat-masuk.show', $surat) }}" class="font-bold text-primary hover:underline">
                                        {{ $surat->nomor_agenda }}
                                    </a>
                                    @if($surat->nomor_surat_asli)
                                        <div class="text-[10px] text-outline font-sans mt-0.5">{{ $surat->nomor_surat_asli }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 font-medium max-w-[200px]">
                                    <span class="truncate block">{{ $surat->pengirim }}</span>
                                </td>
                                <td class="py-3.5 px-6 max-w-sm">
                                    <a href="{{ route('surat-masuk.show', $surat) }}" class="font-bold text-on-surface hover:text-primary transition-colors line-clamp-1">
                                        {{ $surat->perihal }}
                                    </a>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-surface-container text-on-surface-variant">
                                            {{ $surat->kategori }}
                                        </span>
                                        @if($surat->file_path)
                                            <span class="text-[10px] text-outline flex items-center gap-0.5" title="Lampiran Berkas">
                                                📎 Ada Berkas
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-6 text-on-surface-variant tabular-nums whitespace-nowrap">
                                    {{ $surat->tanggal_diterima->isoFormat('D MMM Y') }}
                                </td>
                                <td class="py-3.5 px-6 whitespace-nowrap">
                                    <x-badge-status :status="$surat->status" />
                                </td>
                                <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Tombol Lembar Disposisi -->
                                        <a
                                            href="{{ route('surat-masuk.lembar-disposisi', $surat) }}"
                                            target="_blank"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold border border-border-subtle bg-surface-lowest hover:bg-surface-container transition-colors"
                                            title="Buka / Cetak Lembar Disposisi"
                                        >
                                            📄 Lembar Disposisi
                                        </a>

                                        <!-- Tombol Buat Disposisi (Kepala Sekolah & Admin) -->
                                        @can('createDisposisi', $surat)
                                            <button
                                                type="button"
                                                @click="openDisposisi({{ json_encode($surat) }})"
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-secondary text-on-secondary hover:brightness-95 transition-all shadow-sm"
                                                title="Buat Instruksi Disposisi"
                                            >
                                                ✍️ Disposisi
                                            </button>
                                        @endcan

                                        <!-- Tombol Detail -->
                                        <a
                                            href="{{ route('surat-masuk.show', $surat) }}"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-primary-container text-primary hover:bg-primary hover:text-on-primary transition-colors"
                                            title="Lihat Detail Naskah"
                                        >
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-on-surface-variant">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <p class="font-bold text-base text-on-surface">Tidak ada surat masuk yang sesuai</p>
                                        <p class="text-xs">Coba sesuaikan kata kunci pencarian atau bersihkan penyaring status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            @if($suratList->hasPages())
                <div class="pt-4 mt-4 border-t border-border-subtle">
                    {{ $suratList->links() }}
                </div>
            @endif
        </x-card>

        <!-- ================================================================= -->
        <!-- MODAL: REGISTRASI SURAT MASUK BARU (TATA USAHA & ADMIN)            -->
        <!-- ================================================================= -->
        <div
            x-show="createModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-dark/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
        >
            <div
                class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                @click.away="createModal = false"
                @keydown.escape.window="createModal = false"
            >
                <div class="p-6 border-b border-border-subtle flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">Tambah Surat Masuk</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5">Catat naskah dinas baru ke buku agenda digital SDN Jatipulo 07</p>
                    </div>
                    <button type="button" @click="createModal = false" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container">
                        ✕
                    </button>
                </div>

                <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    <!-- 1. Asal Surat & Nomor Surat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="asal_input" class="block text-sm font-semibold text-on-surface mb-1.5">
                                Asal Surat <span class="text-error">*</span>
                            </label>
                            <select
                                id="asal_input"
                                name="asal"
                                class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                                required
                            >
                                <option value="eksternal" {{ old('asal') === 'eksternal' ? 'selected' : '' }}>Eksternal (Instansi / Lembaga Luar)</option>
                                <option value="internal" {{ old('asal') === 'internal' ? 'selected' : '' }}>Internal (Warga / Unit Sekolah)</option>
                            </select>
                        </div>

                        <x-input
                            name="nomor_surat"
                            label="Nomor Surat Resmi (Opsional)"
                            placeholder="Contoh: 421.2/108/Disdik/2026"
                            value="{{ old('nomor_surat') }}"
                        />
                    </div>

                    <!-- 2. Pengirim & Perihal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            name="pengirim"
                            label="Asal Instansi / Nama Pengirim"
                            placeholder="Contoh: Dinas Pendidikan DKI Jakarta / Komite"
                            value="{{ old('pengirim') }}"
                            required
                        />

                        <x-input
                            name="perihal"
                            label="Perihal Surat"
                            placeholder="Contoh: Undangan Rapat Koordinasi ANBK 2026"
                            value="{{ old('perihal') }}"
                            required
                        />
                    </div>

                    <!-- 3. Tanggal Surat & Tanggal Diterima -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            type="date"
                            name="tanggal_surat"
                            label="Tanggal Surat"
                            value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                            required
                        />
                        <x-input
                            type="date"
                            name="tanggal_diterima"
                            label="Tanggal Diterima TU"
                            value="{{ old('tanggal_diterima', date('Y-m-d')) }}"
                            required
                        />
                    </div>

                    <!-- 4. Kategori & Sifat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori_input" class="block text-sm font-semibold text-on-surface mb-1.5">
                                Kategori Surat <span class="text-error">*</span>
                            </label>
                            <select
                                id="kategori_input"
                                name="kategori"
                                class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                                required
                            >
                                <option value="Dinas">Surat Dinas / Kedinasan Resmi</option>
                                <option value="Undangan">Surat Undangan Rapat / Kegiatan</option>
                                <option value="Edaran">Surat Edaran</option>
                                <option value="Pemberitahuan">Surat Pemberitahuan</option>
                                <option value="Permohonan">Surat Permohonan / Kerjasama</option>
                                <option value="SK Tugas">Surat Keputusan / Tugas</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="sifat_input" class="block text-sm font-semibold text-on-surface mb-1.5">
                                Sifat Urgensi <span class="text-error">*</span>
                            </label>
                            <select
                                id="sifat_input"
                                name="sifat"
                                class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                                required
                            >
                                <option value="Biasa">Biasa</option>
                                <option value="Segera">Segera</option>
                                <option value="Kilat">Kilat</option>
                                <option value="Rahasia">Rahasia</option>
                            </select>
                        </div>
                    </div>

                    <!-- 5. Penerima Surat (Default: Kepala Sekolah) -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">
                            Tujuan Penerima Surat <span class="text-error">*</span>
                        </label>
                        <p class="text-[11px] text-on-surface-variant mb-2">Pilih pihak yang menerima tembusan/tugas surat masuk ini (Otomatis ditujukan ke Kepala Sekolah secara default).</p>
                        <div class="max-h-36 overflow-y-auto p-3 rounded-lg border border-outline-variant bg-surface-container-low/40 space-y-2">
                            @forelse($recipientCandidates as $candidate)
                                <label class="flex items-center gap-2.5 text-xs text-on-surface cursor-pointer hover:text-primary transition-colors select-none">
                                    <input
                                        type="checkbox"
                                        name="penerima_ids[]"
                                        value="{{ $candidate->id }}"
                                        {{ $candidate->role->value === 'kepala_sekolah' ? 'checked' : '' }}
                                        class="rounded border-outline-variant text-primary focus:ring-primary"
                                    >
                                    <span class="font-medium">{{ $candidate->name }}</span>
                                    <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-surface-container text-on-surface-variant">
                                        {{ $candidate->role->label() }}
                                    </span>
                                </label>
                            @empty
                                <p class="text-xs text-on-surface-variant">Tidak ada daftar pengguna yang tersedia.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 6. Ringkasan Isi Surat -->
                    <div>
                        <label for="ringkasan" class="block text-sm font-semibold text-on-surface mb-1.5">
                            Ringkasan / Catatan Isi Surat (Opsional)
                        </label>
                        <textarea
                            id="ringkasan"
                            name="ringkasan"
                            rows="2"
                            placeholder="Uraian singkat pokok surat dinas untuk memudahkan penelusuran arsip..."
                            class="w-full p-3 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                        >{{ old('ringkasan') }}</textarea>
                    </div>

                    <!-- 7. Unggah Berkas Pindaian / Foto Surat -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">
                            Unggah Pindaian / Berkas Fisik Surat (PDF, JPG, PNG - Maks. 5 MB)
                        </label>
                        <input
                            type="file"
                            name="berkas"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-xs text-on-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-container file:text-primary hover:file:bg-primary hover:file:text-on-primary cursor-pointer border border-outline-variant rounded-lg p-1.5 bg-surface-container-low"
                        >
                        <p class="text-[11px] text-on-surface-variant mt-1">Berkas disimpan secara aman di storage privat dinas dengan penamaan acak terenkripsi.</p>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-end gap-3">
                        <x-button type="button" variant="ghost" @click="createModal = false">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="primary">
                            Simpan & Registrasi Surat
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- MODAL: BUAT LEMBAR DISPOSISI PIMPINAN (KEPALA SEKOLAH)             -->
        <!-- ================================================================= -->
        <div
            x-show="disposisiModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-dark/60 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;"
        >
            <div
                class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl max-w-lg w-full"
                @click.away="disposisiModal = false"
                @keydown.escape.window="disposisiModal = false"
            >
                <div class="p-6 border-b border-border-subtle flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">Buat Instruksi Disposisi</h3>
                        <p class="text-xs text-on-surface-variant mt-0.5" x-text="selectedSurat ? selectedSurat.nomor_agenda + ' — ' + selectedSurat.subject : 'Penerusan Naskah Dinas'"></p>
                    </div>
                    <button type="button" @click="disposisiModal = false" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container">
                        ✕
                    </button>
                </div>

                <form
                    method="POST"
                    :action="selectedSurat ? '/surat-masuk/' + selectedSurat.id + '/disposisi' : '#'"
                    class="p-6 space-y-4"
                >
                    @csrf

                    <div>
                        <label for="to_user_id" class="block text-sm font-semibold text-on-surface mb-1.5">
                            Tujuan Disposisi (Penerima Tugas) <span class="text-error">*</span>
                        </label>
                        <select
                            id="to_user_id"
                            name="to_user_id"
                            class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                            required
                        >
                            <option value="">-- Pilih Rekan / Staf --</option>
                            @foreach($assignableUsers as $u)
                                <option value="{{ $u->id }}">
                                    {{ $u->name }} ({{ strtoupper(str_replace('_', ' ', $u->role->value ?? $u->role)) }}{{ $u->unit ? ' - ' . $u->unit : '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sifat_disp" class="block text-sm font-semibold text-on-surface mb-1.5">
                                Urgensi
                            </label>
                            <select
                                id="sifat_disp"
                                name="sifat"
                                class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                            >
                                <option value="Biasa">Biasa</option>
                                <option value="Segera">Segera</option>
                                <option value="Kilat">Kilat</option>
                            </select>
                        </div>

                        <div>
                            <x-input
                                type="date"
                                name="batas_waktu"
                                label="Batas Waktu"
                                value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="instruksi" class="block text-sm font-semibold text-on-surface mb-1.5">
                            Petunjuk / Catatan Instruksi Disposisi <span class="text-error">*</span>
                        </label>
                        <textarea
                            id="instruksi"
                            name="instruksi"
                            rows="3"
                            placeholder="Contoh: Tindak lanjuti koordinasi dengan pihak terkait dan buatkan laporan pelaksanaan tugas..."
                            class="w-full p-3 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                            required
                        ></textarea>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-end gap-3">
                        <x-button type="button" variant="ghost" @click="disposisiModal = false">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="accent">
                            Kirim Disposisi
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>


<x-app-layout active="surat_masuk">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('surat-masuk.index') }}" class="p-2 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors" title="Kembali ke Daftar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold font-mono text-primary leading-tight">
                            {{ $suratMasuk->nomor_agenda }}
                        </h2>
                        <x-badge-status :status="$suratMasuk->status" />
                    </div>
                    <p class="text-xs text-on-surface-variant mt-0.5 truncate max-w-lg">
                        {{ $suratMasuk->perihal }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('surat-masuk.lembar-disposisi', $suratMasuk) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold border border-border-subtle bg-surface-lowest hover:bg-surface-container transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Lembar Disposisi</span>
                </a>

                @can('create', \App\Models\SuratKeluar::class)
                    <a
                        href="{{ route('surat-keluar.index', ['parent_surat_masuk_id' => $suratMasuk->id]) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-primary text-on-primary hover:bg-primary-strong transition-all shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <span>Buat Surat Balasan</span>
                    </a>
                @endcan

                @can('createDisposisi', $suratMasuk)
                    <button
                        type="button"
                        @click="$dispatch('open-disp-modal')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-secondary text-on-secondary hover:brightness-95 transition-all shadow-sm"
                    >
                        <span>+ Teruskan Disposisi</span>
                    </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ dispModal: false }" @open-disp-modal.window="dispModal = true">
        @if(session('disposisi_created_ids'))
            @php
                $createdDispIds = (array) session('disposisi_created_ids');
                $createdWithWa = $suratMasuk->disposisi->whereIn('id', $createdDispIds)->filter(fn ($d) => !empty($whatsappLinks[$d->id] ?? null));
            @endphp
            @if($createdWithWa->isNotEmpty())
                <div class="p-4 rounded-2xl bg-success-container text-on-success-container border border-success/30 space-y-2.5" role="status">
                    <p class="text-xs font-bold">Disposisi berhasil dibuat. Kirim pemberitahuan WhatsApp langsung ke penerima:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($createdWithWa as $dispItem)
                            <a
                                href="{{ $whatsappLinks[$dispItem->id] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary/90 transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>Kirim via WhatsApp ({{ $dispItem->toUser?->name }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Metadata Surat -->
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Metadata Naskah Dinas" subtitle="Rincian informasi surat masuk yang terdaftar di sistem">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-xs">
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Nomor Surat Pengirim</dt>
                            <dd class="mt-1 font-semibold text-on-surface font-mono">{{ $suratMasuk->nomor_surat_asli ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Asal / Pengirim</dt>
                            <dd class="mt-1 font-bold text-on-surface text-sm">{{ $suratMasuk->pengirim }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Perihal Surat</dt>
                            <dd class="mt-1 text-sm font-semibold text-on-surface leading-relaxed">{{ $suratMasuk->perihal }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Tanggal Surat</dt>
                            <dd class="mt-1 font-medium text-on-surface tabular-nums">{{ $suratMasuk->tanggal_surat->isoFormat('dddd, D MMMM Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Tanggal Diterima TU</dt>
                            <dd class="mt-1 font-medium text-on-surface tabular-nums">{{ $suratMasuk->tanggal_diterima->isoFormat('dddd, D MMMM Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Kategori & Sifat</dt>
                            <dd class="mt-1 flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-surface-container font-semibold text-on-surface">{{ $suratMasuk->kategori }}</span>
                                <span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container font-semibold">{{ $suratMasuk->sifat }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Petugas Pencatat</dt>
                            <dd class="mt-1 font-medium text-on-surface">{{ $suratMasuk->creator->name ?? 'Staf Tata Usaha' }}</dd>
                        </div>
                        @if($suratMasuk->catatan)
                            <div class="sm:col-span-2 pt-2 border-t border-border-subtle">
                                <dt class="font-bold text-outline uppercase tracking-wider text-[10px]">Catatan Khusus</dt>
                                <dd class="mt-1 text-on-surface-variant italic">{{ $suratMasuk->catatan }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-card>

                <!-- Riwayat & Alur Disposisi Bertingkat -->
                <x-card title="Alur & Instruksi Disposisi" subtitle="Daftar arahan pimpinan dan rekam tindak lanjut penerima tugas">
                    @if($suratMasuk->disposisi->isEmpty())
                        <div class="py-8 text-center text-on-surface-variant">
                            <p class="font-semibold text-sm">Belum ada lembar instruksi disposisi.</p>
                            <p class="text-xs mt-1">Surat masih berstatus diterima di loket Tata Usaha.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($suratMasuk->disposisi as $disp)
                                <div class="p-4 rounded-xl border border-border-subtle bg-surface-container-low/30 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-on-surface text-xs">{{ $disp->fromUser->name }} (Pemberi)</span>
                                                <span class="text-outline text-xs">➔</span>
                                                <span class="font-bold text-primary text-xs">{{ $disp->toUser->name }} (Penerima)</span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 text-[11px] text-outline">
                                                <span>Urgensi: <strong>{{ $disp->sifat }}</strong></span>
                                                @if($disp->batas_waktu)
                                                    <span>• Batas Waktu: <strong>{{ $disp->batas_waktu->isoFormat('D MMM Y') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <x-badge-status :status="$disp->status" />
                                    </div>

                                    <!-- Isi Instruksi -->
                                    <div class="p-3 rounded-lg bg-surface-lowest border border-border-subtle text-xs text-on-surface">
                                        <p class="font-semibold text-[10px] uppercase tracking-wider text-outline mb-1">Instruksi Pimpinan:</p>
                                        <p class="leading-relaxed">{{ $disp->instruksi }}</p>
                                    </div>

                                    @if(!empty($whatsappLinks[$disp->id]))
                                        <div class="flex justify-end">
                                            <a
                                                href="{{ $whatsappLinks[$disp->id] }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-subtle bg-surface-lowest text-xs font-semibold text-primary hover:bg-primary-container transition-colors"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                <span>Kirim via WhatsApp</span>
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Catatan Tindak Lanjut Penerima -->
                                    @if($disp->catatan_tindak_lanjut)
                                        <div class="p-3 rounded-lg bg-success-container/30 border border-success/20 text-xs text-on-surface">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="font-semibold text-[10px] uppercase tracking-wider text-success">Laporan Tindak Lanjut:</p>
                                                @if($disp->selesai_pada)
                                                    <span class="text-[10px] text-outline tabular-nums">{{ $disp->selesai_pada->isoFormat('D MMM Y, HH:mm') }} WIB</span>
                                                @endif
                                            </div>
                                            <p class="leading-relaxed text-on-surface">{{ $disp->catatan_tindak_lanjut }}</p>
                                        </div>
                                    @endif

                                    <!-- Form Tindak Lanjut Jika Yang Login adalah Penerima Tugas -->
                                    @if($disp->to_user_id === auth()->id() && $disp->status !== 'Selesai')
                                        <div class="pt-3 border-t border-border-subtle">
                                            <form method="POST" action="{{ route('disposisi.update', $disp) }}" class="space-y-3">
                                                @csrf
                                                @method('PATCH')

                                                <div>
                                                    <label for="catatan_tl_{{ $disp->id }}" class="block text-xs font-bold text-on-surface mb-1">
                                                        Perbarui Progres / Catatan Tindak Lanjut Anda:
                                                    </label>
                                                    <textarea
                                                        id="catatan_tl_{{ $disp->id }}"
                                                        name="catatan_tindak_lanjut"
                                                        rows="2"
                                                        placeholder="Tuliskan tindakan yang telah diambil atau laporan penyelesaian..."
                                                        class="w-full p-2.5 rounded-lg text-xs bg-surface-lowest border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                                                        required
                                                    >{{ old('catatan_tindak_lanjut', $disp->catatan_tindak_lanjut) }}</textarea>
                                                </div>

                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface cursor-pointer">
                                                            <input type="radio" name="status" value="Diproses" {{ $disp->status === 'Diproses' ? 'checked' : '' }}>
                                                            <span>Sedang Diproses</span>
                                                        </label>
                                                        <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-success cursor-pointer">
                                                            <input type="radio" name="status" value="Selesai" {{ $disp->status === 'Selesai' ? 'checked' : '' }}>
                                                            <span>Telah Selesai</span>
                                                        </label>
                                                    </div>

                                                    <x-button type="submit" variant="primary" size="sm">
                                                        Simpan Laporan
                                                    </x-button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Kolom Kanan: Berkas Lampiran & Surat Balasan -->
            <div class="space-y-6">
                <x-card title="Berkas Naskah Dinas" subtitle="Dokumen fisik yang dipindai ke storage privat">
                    @if($suratMasuk->file_path)
                        <div class="p-4 rounded-xl border border-border-subtle bg-surface-container-low/40 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-container text-primary flex items-center justify-center font-bold text-sm">
                                    PDF
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-xs text-on-surface truncate">{{ $suratMasuk->file_name }}</p>
                                    <p class="text-[10px] text-outline tabular-nums">
                                        {{ number_format(($suratMasuk->file_size ?? 0) / 1024, 1) }} KB • Terproteksi Policy
                                    </p>
                                </div>
                            </div>

                            <a
                                href="{{ route('surat-masuk.download', $suratMasuk) }}"
                                class="w-full inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs hover:bg-primary-strong transition-colors shadow-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Unduh Berkas Asli</span>
                            </a>
                        </div>
                    @else
                        <div class="py-6 text-center text-on-surface-variant text-xs">
                            <p class="italic">Tidak ada berkas digital terlampir pada surat ini.</p>
                        </div>
                    @endif
                </x-card>

                <!-- Surat Balasan / Surat Keluar Terkait -->
                <x-card title="Surat Balasan Terkait" subtitle="Naskah dinas resmi sekolah yang diterbitkan sebagai balasan surat ini">
                    @if($suratMasuk->suratKeluar->isEmpty())
                        <div class="py-6 text-center text-on-surface-variant text-xs space-y-2">
                            <p class="italic">Belum ada surat keluar yang diterbitkan sebagai balasan.</p>
                            @can('create', \App\Models\SuratKeluar::class)
                                <a
                                    href="{{ route('surat-keluar.index', ['parent_surat_masuk_id' => $suratMasuk->id]) }}"
                                    class="inline-flex items-center gap-1 text-primary hover:underline font-semibold text-xs"
                                >
                                    + Buat Surat Balasan Resmi
                                </a>
                            @endcan
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($suratMasuk->suratKeluar as $sk)
                                <a
                                    href="{{ route('surat-keluar.show', $sk) }}"
                                    class="block p-3 rounded-xl border border-border-subtle bg-surface-lowest hover:bg-surface-container-low transition-colors group"
                                >
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-mono font-bold text-xs text-primary group-hover:underline">
                                            {{ $sk->nomor_agenda }}
                                        </span>
                                        <x-badge-status :status="$sk->status" />
                                    </div>
                                    <p class="font-semibold text-xs text-on-surface mt-1 line-clamp-1">
                                        {{ $sk->perihal }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px] text-outline mt-2 pt-2 border-t border-border-subtle/50">
                                        <span class="truncate max-w-[140px]">Tujuan: <strong>{{ $sk->tujuan }}</strong></span>
                                        <span class="tabular-nums whitespace-nowrap">{{ $sk->tanggal_surat->isoFormat('D MMM Y') }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </x-card>
            </div>

        </div>

        <!-- Modal Tambah Disposisi Multi-Assignee -->
        @can('createDisposisi', $suratMasuk)
            <div
                x-show="dispModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-dark/60 backdrop-blur-xs"
                x-transition
                style="display: none;"
                x-data="{
                    selectedRecipients: [],
                    selectAll() {
                        this.selectedRecipients = [{{ $assignableUsers->pluck('id')->join(',') }}];
                    },
                    clearAll() {
                        this.selectedRecipients = [];
                    }
                }"
            >
                <div
                    class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl max-w-lg w-full overflow-hidden"
                    @click.away="dispModal = false"
                >
                    <div class="p-6 border-b border-border-subtle flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-on-surface">Buat Instruksi Disposisi</h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $suratMasuk->nomor_agenda }} — {{ $suratMasuk->perihal }}</p>
                        </div>
                        <button type="button" @click="dispModal = false" class="p-1.5 rounded-lg text-outline hover:text-on-surface">✕</button>
                    </div>

                    <form method="POST" action="{{ route('disposisi.store', $suratMasuk) }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-sm font-semibold text-on-surface">
                                    Pilih Penerima Disposisi <span class="text-error">*</span>
                                </label>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" @click="selectAll()" class="text-primary hover:underline font-semibold">Pilih Semua</button>
                                    <span class="text-outline">•</span>
                                    <button type="button" @click="clearAll()" class="text-outline hover:text-on-surface">Hapus Pilihan</button>
                                </div>
                            </div>

                            <div class="max-h-48 overflow-y-auto border border-outline-variant rounded-xl p-2.5 space-y-1.5 bg-surface-lowest">
                                @forelse($assignableUsers as $u)
                                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-container transition-colors cursor-pointer text-xs">
                                        <input
                                            type="checkbox"
                                            name="to_user_ids[]"
                                            value="{{ $u->id }}"
                                            x-model="selectedRecipients"
                                            class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4 cursor-pointer"
                                        >
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-on-surface truncate">{{ $u->name }}</p>
                                            <p class="text-[11px] text-outline truncate">
                                                {{ strtoupper(str_replace('_', ' ', $u->role->value ?? $u->role)) }}{{ $u->unit ? ' - ' . $u->unit : '' }}
                                            </p>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-outline py-2 text-center">Tidak ada calon penerima tersedia.</p>
                                @endforelse
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-outline mt-1 px-1">
                                <span>Bisa memilih lebih dari satu penerima sekaligus.</span>
                                <span class="font-semibold text-primary" x-text="selectedRecipients.length + ' dipilih'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="sifat_disp_show" class="block text-sm font-semibold text-on-surface mb-1.5">Urgensi</label>
                                <select id="sifat_disp_show" name="sifat" class="w-full h-11 px-3.5 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none">
                                    <option value="Biasa">Biasa</option>
                                    <option value="Segera">Segera</option>
                                    <option value="Kilat">Kilat</option>
                                </select>
                            </div>
                            <div>
                                <x-input type="date" name="batas_waktu" label="Batas Waktu" value="{{ date('Y-m-d', strtotime('+3 days')) }}" />
                            </div>
                        </div>

                        <div>
                            <label for="instruksi_show" class="block text-sm font-semibold text-on-surface mb-1.5">
                                Instruksi / Catatan Disposisi <span class="text-error">*</span>
                            </label>
                            <textarea id="instruksi_show" name="instruksi" rows="3" class="w-full p-3 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none" required placeholder="Catatan instruksi arahan pimpinan..."></textarea>
                        </div>

                        <div class="pt-4 border-t border-border-subtle flex items-center justify-end gap-3">
                            <x-button type="button" variant="ghost" @click="dispModal = false">Batal</x-button>
                            <x-button type="submit" variant="accent" ::disabled="selectedRecipients.length === 0">Teruskan Disposisi</x-button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

    </div>
</x-app-layout>


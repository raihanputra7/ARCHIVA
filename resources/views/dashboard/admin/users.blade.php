<x-app-layout active="admin_users">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Pemantauan &amp; Tata Kelola Akun ARCHIVA
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                    Kelola akun pengguna per peran, pantau aktivitas sesi, dan bantu pemulihan kata sandi staf sekolah
                </p>
            </div>
            <div>
                <button
                    type="button"
                    x-data
                    @click="$dispatch('open-modal', 'create-admin-user')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold text-xs shadow-xs hover:bg-primary/90 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Akun Baru</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div
        class="space-y-6"
        x-data="{
            resetUserId: null,
            resetUserName: '',
            resetUserEmail: '',
            openResetModal(id, name, email) {
                this.resetUserId = id;
                this.resetUserName = name;
                this.resetUserEmail = email;
                $dispatch('open-modal', 'reset-user-password');
            }
        }"
    >
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-medium" role="alert">
                <p class="font-bold mb-1">Periksa kembali data yang Anda masukkan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Ringkasan & Filter -->
        <x-card>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 w-full lg:w-auto">
                    @foreach($roles as $roleCase)
                        <a
                            href="{{ route('dashboard.admin.users', ['role' => ($roleFilter === $roleCase->value ? null : $roleCase->value), 'q' => $search]) }}"
                            class="px-3 py-2 rounded-lg border text-left transition-colors {{ $roleFilter === $roleCase->value ? 'border-primary bg-primary-container text-primary' : 'border-border-subtle bg-surface-container-low/50 hover:bg-surface-container text-on-surface' }}"
                        >
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-on-surface-variant truncate">{{ $roleCase->label() }}</p>
                            <p class="text-base font-extrabold tabular-nums mt-0.5">{{ $monitoring['roleCounts'][$roleCase->value] ?? 0 }}</p>
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('dashboard.admin.users') }}" class="flex items-center gap-2 w-full lg:w-auto">
                    @if($roleFilter)
                        <input type="hidden" name="role" value="{{ $roleFilter }}">
                    @endif
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, unit..."
                        class="h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface placeholder:text-outline border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none w-full sm:w-64"
                    >
                    <button
                        type="submit"
                        class="h-10 px-4 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary/90 transition-colors cursor-pointer"
                    >
                        Cari
                    </button>
                    @if($search || $roleFilter)
                        <a
                            href="{{ route('dashboard.admin.users') }}"
                            class="h-10 px-3 inline-flex items-center rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </x-card>

        <!-- Tabel Lengkap Akun -->
        <x-card title="Daftar Akun Pengguna ({{ $monitoring['accounts']->count() }})" subtitle="Informasi akun terdaftar di sistem ARCHIVA SDN Jatipulo 07">
            <div class="overflow-x-auto -mx-6 -mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant uppercase tracking-wider font-bold border-y border-border-subtle">
                            <th class="py-3 px-6">Nama Pengguna</th>
                            <th class="py-3 px-4">Email Kedinasan</th>
                            <th class="py-3 px-4">Peran &amp; Unit</th>
                            <th class="py-3 px-4">Aktivitas Sesi</th>
                            <th class="py-3 px-4">Disposisi Diterima</th>
                            <th class="py-3 px-6 text-right">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @forelse($monitoring['accounts'] as $account)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($account['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface">{{ $account['name'] }}</p>
                                            <p class="text-[11px] text-outline">
                                                ID #{{ $account['id'] }} &middot; Dibuat {{ $account['created_at']?->isoFormat('D MMM Y') ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-medium text-on-surface">{{ $account['email'] }}</p>
                                    <p class="text-[11px] {{ $account['email_verified'] ? 'text-success' : 'text-outline' }}">
                                        {{ $account['email_verified'] ? 'Email terverifikasi' : 'Belum terverifikasi' }}
                                    </p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-semibold text-on-surface">{{ $account['role_label'] }}</p>
                                    @if($account['unit'])
                                        <p class="text-[11px] text-on-surface-variant">Unit: {{ $account['unit'] }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $account['is_online'] ? 'bg-success' : 'bg-outline-variant' }}"></span>
                                        <span class="font-medium {{ $account['is_online'] ? 'text-success' : 'text-on-surface-variant' }}">
                                            {{ $account['is_online'] ? 'Sedang Aktif' : $account['last_active_human'] }}
                                        </span>
                                    </div>
                                    @if($account['ip_address'])
                                        <p class="text-[11px] text-outline mt-0.5">IP: {{ $account['ip_address'] }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 tabular-nums font-semibold text-on-surface">
                                    {{ $account['disposisi_count'] }} Tugas
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a
                                            href="mailto:{{ $account['email'] }}?subject={{ rawurlencode('[ARCHIVA] Dukungan Akun Pengguna') }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-border-subtle bg-surface-lowest hover:bg-surface-container text-on-surface font-semibold transition-colors"
                                        >
                                            Hubungi
                                        </a>
                                        <button
                                            type="button"
                                            @click="openResetModal({{ $account['id'] }}, '{{ addslashes($account['name']) }}', '{{ addslashes($account['email']) }}')"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-container text-primary hover:bg-primary/15 font-semibold transition-colors cursor-pointer"
                                        >
                                            Reset Sandi
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-6 text-center text-on-surface-variant">
                                    Belum ada akun yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Modal Tambah Akun Baru -->
        <x-modal name="create-admin-user" maxWidth="lg" focusable>
            <form method="POST" action="{{ route('dashboard.admin.users.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Tambah Akun Pengguna ARCHIVA</h3>
                    <p class="text-xs text-on-surface-variant mt-1">Daftarkan akun resmi baru untuk staf, guru, atau pimpinan sekolah.</p>
                </div>

                <div>
                    <x-input-label for="create_name" value="Nama Lengkap" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1.5 block w-full" placeholder="Contoh: Ibu Siti Aminah, S.Pd." required />
                </div>

                <div>
                    <x-input-label for="create_email" value="Email Kedinasan" />
                    <x-text-input id="create_email" name="email" type="email" class="mt-1.5 block w-full" placeholder="nama@archiva.test" required />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_role" value="Peran" />
                        <select
                            id="create_role"
                            name="role"
                            required
                            class="mt-1.5 block w-full h-11 px-3 rounded-lg text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                        >
                            @foreach($roles as $roleCase)
                                <option value="{{ $roleCase->value }}">{{ $roleCase->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_unit" value="Unit / Bidang (Opsional)" />
                        <x-text-input id="create_unit" name="unit" type="text" class="mt-1.5 block w-full" placeholder="Contoh: Dapodik / Kurikulum" />
                    </div>
                </div>

                <div>
                    <x-input-label for="create_password" value="Kata Sandi Awal" />
                    <x-text-input id="create_password" name="password" type="password" class="mt-1.5 block w-full" placeholder="Minimal 8 karakter" required />
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button>Simpan Akun</x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Reset Kata Sandi -->
        <x-modal name="reset-user-password" maxWidth="md" focusable>
            <form
                method="POST"
                :action="`{{ url('/dashboard/admin/akun') }}/${resetUserId}/reset-password`"
                class="p-6 space-y-4"
            >
                @csrf
                @method('PATCH')
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Reset Kata Sandi Pengguna</h3>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Tetapkan kata sandi baru untuk <strong class="text-on-surface" x-text="resetUserName"></strong> (<span x-text="resetUserEmail"></span>).
                    </p>
                </div>

                <div>
                    <x-input-label for="reset_new_password" value="Kata Sandi Baru" />
                    <x-text-input id="reset_new_password" name="new_password" type="password" class="mt-1.5 block w-full" placeholder="Minimal 8 karakter" required />
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button>Simpan Sandi Baru</x-primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>


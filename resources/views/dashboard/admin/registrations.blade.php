<x-app-layout active="admin_registrations">
    <x-slot name="header">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight leading-tight">
                Permintaan Pendaftaran Akun
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
                Tinjau pendaftar baru, verifikasi email, tentukan peran final, atau kelola status aktif akun pengguna
            </p>
        </div>
    </x-slot>

    <div
        class="space-y-6"
        x-data="{
            rejectUserId: null,
            rejectUserName: '',
            editUserId: null,
            editUserName: '',
            editUserEmail: '',
            editUserPhone: '',
            editUserRole: '',
            openRejectModal(id, name) {
                this.rejectUserId = id;
                this.rejectUserName = name;
                $dispatch('open-modal', 'reject-registration-modal');
            },
            openEditModal(id, name, email, phone, role) {
                this.editUserId = id;
                this.editUserName = name;
                this.editUserEmail = email;
                this.editUserPhone = phone || '';
                this.editUserRole = role;
                $dispatch('open-modal', 'edit-user-contact-modal');
            }
        }"
    >
        @if ($errors->any())
            <div class="p-3.5 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-medium" role="alert">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-card>
            <form method="GET" action="{{ route('dashboard.admin.registrations') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    @php
                        $filterTabs = [
                            'pending' => 'Menunggu (' . $pendingCount . ')',
                            'active' => 'Aktif',
                            'rejected' => 'Ditolak',
                            'inactive' => 'Nonaktif',
                            'all' => 'Semua',
                        ];
                    @endphp
                    @foreach($filterTabs as $tabKey => $tabLabel)
                        <a
                            href="{{ route('dashboard.admin.registrations', ['status' => $tabKey, 'q' => $search]) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors {{ $statusFilter === $tabKey ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-low text-on-surface-variant border-border-subtle hover:bg-surface-container' }}"
                        >
                            {{ $tabLabel }}
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, atau WA..."
                        class="h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface placeholder:text-outline border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none w-full sm:w-64"
                    >
                    <button
                        type="submit"
                        class="h-10 px-4 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary/90 transition-colors cursor-pointer"
                    >
                        Cari
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Daftar Pendaftar &amp; Status Akun" subtitle="Pendaftar hanya dapat disetujui setelah alamat emailnya terverifikasi">
            @if($registrations->isEmpty())
                <div class="py-10 text-center text-xs text-on-surface-variant">
                    Tidak ada data pendaftaran untuk filter ini.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($registrations as $applicant)
                        @php
                            $accountStatus = \App\Enums\AccountStatus::tryFrom((string) ($applicant->status ?? 'active')) ?? \App\Enums\AccountStatus::ACTIVE;
                            $currentRoleVal = $applicant->role instanceof \App\Enums\Role ? $applicant->role->value : (string) $applicant->role;
                            $requestedRoleLabel = \App\Enums\Role::tryFrom((string) $applicant->requested_role)?->label()
                                ?? (\App\Enums\Role::tryFrom($currentRoleVal)?->label() ?? $currentRoleVal);
                            $isEmailVerified = $applicant->email_verified_at !== null;
                        @endphp

                        <div class="p-4 rounded-2xl border border-border-subtle bg-surface-container-low/30 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-bold text-on-surface">{{ $applicant->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $accountStatus->badgeClasses() }}">
                                        {{ $accountStatus->label() }}
                                    </span>
                                    @if($isEmailVerified)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-success-container text-on-success-container">
                                            Email Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-warning-container text-on-warning-container">
                                            Email Belum Verifikasi
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-on-surface-variant">
                                    <span>Email: <strong class="text-on-surface">{{ $applicant->email }}</strong></span>
                                    <span>WA: <strong class="text-on-surface tabular-nums">{{ $applicant->phone_wa ?: '-' }}</strong></span>
                                    <span>Peran Diajukan: <strong class="text-primary">{{ $requestedRoleLabel }}</strong></span>
                                    <span>Daftar: <strong class="tabular-nums">{{ $applicant->created_at?->isoFormat('D MMM Y, HH:mm') }}</strong></span>
                                </div>

                                @if($applicant->rejected_reason)
                                    <p class="text-xs text-error">
                                        Alasan penolakan: {{ $applicant->rejected_reason }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                                @if($accountStatus === \App\Enums\AccountStatus::PENDING)
                                    <form method="POST" action="{{ route('dashboard.admin.registrations.approve', $applicant) }}" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select
                                            name="role"
                                            aria-label="Peran final untuk {{ $applicant->name }}"
                                            class="h-9 px-2.5 rounded-lg text-xs bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:outline-none"
                                        >
                                            @foreach($roles as $roleCase)
                                                <option value="{{ $roleCase->value }}" {{ ($applicant->requested_role ?? $currentRoleVal) === $roleCase->value ? 'selected' : '' }}>
                                                    {{ $roleCase->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button
                                            type="submit"
                                            @disabled(!$isEmailVerified)
                                            title="{{ $isEmailVerified ? 'Setujui & aktifkan akun' : 'Menunggu pendaftar memverifikasi email' }}"
                                            class="h-9 px-3.5 rounded-lg text-xs font-bold transition-colors {{ $isEmailVerified ? 'bg-primary text-on-primary hover:bg-primary/90 cursor-pointer' : 'bg-surface-container text-outline cursor-not-allowed' }}"
                                        >
                                            Setujui
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        @click="openRejectModal({{ $applicant->id }}, @js($applicant->name))"
                                        class="h-9 px-3 rounded-lg border border-error/40 text-error text-xs font-semibold hover:bg-error-container transition-colors cursor-pointer"
                                    >
                                        Tolak
                                    </button>
                                @elseif(in_array($accountStatus, [\App\Enums\AccountStatus::ACTIVE, \App\Enums\AccountStatus::INACTIVE], true) && $applicant->id !== auth()->id())
                                    <form method="POST" action="{{ route('dashboard.admin.users.toggle-status', $applicant) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="h-9 px-3 rounded-lg border border-border-subtle bg-surface-lowest text-xs font-semibold hover:bg-surface-container transition-colors cursor-pointer {{ $accountStatus === \App\Enums\AccountStatus::ACTIVE ? 'text-error' : 'text-success' }}"
                                        >
                                            {{ $accountStatus === \App\Enums\AccountStatus::ACTIVE ? 'Nonaktifkan' : 'Aktifkan Kembali' }}
                                        </button>
                                    </form>
                                @endif

                                <button
                                    type="button"
                                    @click="openEditModal({{ $applicant->id }}, @js($applicant->name), @js($applicant->email), @js($applicant->phone_wa), @js($currentRoleVal))"
                                    class="h-9 px-3 rounded-lg border border-border-subtle bg-surface-lowest text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors cursor-pointer"
                                >
                                    Edit Kontak/Peran
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($registrations->hasPages())
                    <div class="mt-6 pt-4 border-t border-border-subtle">
                        {{ $registrations->links() }}
                    </div>
                @endif
            @endif
        </x-card>

        <!-- Modal Tolak Pendaftaran -->
        <x-modal name="reject-registration-modal" maxWidth="md">
            <form method="POST" :action="`{{ url('/dashboard/admin/permintaan-pendaftaran') }}/${rejectUserId}/reject`" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <h3 class="text-base font-bold text-on-surface">Tolak Pendaftaran Akun</h3>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Pendaftar <strong class="text-on-surface" x-text="rejectUserName"></strong> akan menerima email pemberitahuan penolakan.
                    </p>
                </div>

                <div>
                    <label for="rejected_reason" class="block text-xs font-semibold text-on-surface mb-1">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea
                        id="rejected_reason"
                        name="rejected_reason"
                        rows="3"
                        maxlength="500"
                        placeholder="Contoh: Data tidak ditemukan pada daftar pegawai aktif SDN Jatipulo 07."
                        class="w-full p-2.5 rounded-lg text-xs bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:outline-none"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'reject-registration-modal')">
                        Batal
                    </x-secondary-button>
                    <x-danger-button type="submit">
                        Tolak Pendaftaran
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Edit Email, WA & Peran -->
        <x-modal name="edit-user-contact-modal" maxWidth="md">
            <form method="POST" :action="`{{ url('/dashboard/admin/akun') }}/${editUserId}`" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <h3 class="text-base font-bold text-on-surface">Edit Kontak &amp; Peran Pengguna</h3>
                    <p class="text-xs text-on-surface-variant mt-1" x-text="editUserName"></p>
                </div>

                <div>
                    <label for="edit_email" class="block text-xs font-semibold text-on-surface mb-1">Email</label>
                    <input
                        id="edit_email"
                        name="email"
                        type="email"
                        required
                        x-model="editUserEmail"
                        class="w-full h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:outline-none"
                    >
                </div>

                <div>
                    <label for="edit_phone_wa" class="block text-xs font-semibold text-on-surface mb-1">Nomor WhatsApp</label>
                    <input
                        id="edit_phone_wa"
                        name="phone_wa"
                        type="tel"
                        x-model="editUserPhone"
                        placeholder="6281234567890"
                        class="w-full h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:outline-none"
                    >
                </div>

                <div>
                    <label for="edit_role" class="block text-xs font-semibold text-on-surface mb-1">Peran</label>
                    <select
                        id="edit_role"
                        name="role"
                        x-model="editUserRole"
                        class="w-full h-10 px-3 rounded-lg text-xs bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:outline-none"
                    >
                        @foreach($roles as $roleCase)
                            <option value="{{ $roleCase->value }}">{{ $roleCase->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <x-secondary-button type="button" @click="$dispatch('close-modal', 'edit-user-contact-modal')">
                        Batal
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        Simpan Perubahan
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>


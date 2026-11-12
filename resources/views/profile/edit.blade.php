<x-app-layout active="profile">
    <x-slot name="header">
        <h2 class="font-bold text-xl text-on-surface leading-tight">
            Pengaturan Akun Pengguna
        </h2>
    </x-slot>

    <div class="space-y-6">
        <x-card title="Informasi Profil" subtitle="Perbarui nama akun dan alamat email kedinasan Anda.">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-card>

        <x-card title="Perbarui Kata Sandi" subtitle="Pastikan akun Anda menggunakan kata sandi yang aman untuk menjaga kerahasiaan persuratan sekolah.">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-card>

        @if(isset($adminContacts) && $adminContacts->isNotEmpty())
            <x-card title="Bantuan &amp; Kontak Administrator" subtitle="Jika mengalami kendala akses, perubahan peran, atau lupa kata sandi, hubungi Administrator ARCHIVA berikut:">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-2xl">
                    @foreach($adminContacts as $adminContact)
                        <div class="p-3.5 rounded-xl border border-border-subtle bg-surface-container-low/50 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-on-surface truncate">{{ $adminContact->name }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ $adminContact->email }}</p>
                            </div>
                            <a
                                href="mailto:{{ $adminContact->email }}?subject={{ rawurlencode('[ARCHIVA] Bantuan Akun Pengguna') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary/90 transition-colors flex-shrink-0"
                            >
                                Hubungi
                            </a>
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif

        <x-card title="Hapus Akun" subtitle="Tindakan permanen untuk menghapus akun Anda dari sistem.">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-card>
    </div>
</x-app-layout>

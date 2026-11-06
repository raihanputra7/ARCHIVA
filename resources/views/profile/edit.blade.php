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

        <x-card title="Hapus Akun" subtitle="Tindakan permanen untuk menghapus akun Anda dari sistem.">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-card>
    </div>
</x-app-layout>

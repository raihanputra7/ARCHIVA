<section>
    <form id="send-verification" method="post" action="{{ route('register.resend-verification') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $user->email }}">
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if (! $user->isEmailVerified())
                <div class="mt-2 p-3 rounded-xl bg-warning-container text-on-warning-container border border-warning/30">
                    <p class="text-xs">
                        Alamat email Anda belum terverifikasi.

                        <button form="send-verification" class="underline text-xs font-semibold hover:opacity-80 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Klik di sini untuk mengirim ulang tautan verifikasi.
                        </button>
                    </p>
                </div>
            @endif

            @if (session('status') === 'profile-email-verification-sent')
                <p class="mt-2 font-medium text-xs text-primary">
                    Email berhasil diubah. Tautan verifikasi baru telah dikirim ke alamat email Anda.
                </p>
            @endif
        </div>

        <div>
            <x-input-label for="phone_wa" value="Nomor WhatsApp" />
            <x-text-input id="phone_wa" name="phone_wa" type="tel" class="mt-1.5 block w-full" :value="old('phone_wa', $user->phone_wa)" placeholder="081234567890 atau 6281234567890" autocomplete="tel" />
            <p class="text-[11px] text-on-surface-variant mt-1">Format 08xx, 62xx, atau +62xx akan dinormalisasi ke 62xxxxxxxxxx.</p>
            <x-input-error class="mt-1.5" :messages="$errors->get('phone_wa')" />
        </div>

        <div class="pt-1">
            <label for="notify_email" class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                <input
                    id="notify_email"
                    name="notify_email"
                    type="checkbox"
                    value="1"
                    {{ old('notify_email', $user->notify_email ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary"
                >
                <span class="text-xs font-medium text-on-surface">
                    Terima notifikasi email otomatis saat saya menerima disposisi surat baru
                </span>
            </label>
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>Simpan Perubahan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-success"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>

<x-guest-layout title="Pendaftaran Akun — ARCHIVA SDN Jatipulo 07">
    <div x-data class="min-h-screen w-full bg-surface flex items-center justify-center p-3 sm:p-6">
        <div class="fixed top-3.5 right-3.5 z-50">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="p-2 rounded-xl bg-surface-lowest border border-border-subtle text-on-surface shadow-xs hover:bg-surface-container transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                :title="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                :aria-label="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
            >
                <svg x-show="$store.theme.dark" x-cloak class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                </svg>
                <svg x-show="!$store.theme.dark" x-cloak class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>

        <div class="w-full max-w-xl mx-auto">
            <div class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl overflow-hidden p-6 sm:p-8">
                <div class="mb-5">
                    <div class="mb-3">
                        <x-logo size="sm" />
                    </div>
                    <h1 class="text-2xl font-extrabold text-on-surface tracking-tight leading-tight">
                        Pendaftaran Akun Baru
                    </h1>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Ajukan akun portal persuratan SDN Jatipulo 07. Akun akan aktif setelah verifikasi email dan persetujuan Administrator.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-medium" role="alert">
                        <p class="font-bold mb-1">Periksa kembali data pendaftaran:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold text-on-surface mb-1.5">
                            Nama Lengkap <span class="text-error" aria-hidden="true">*</span>
                        </label>
                        <x-input
                            id="name"
                            name="name"
                            type="text"
                            :value="old('name')"
                            placeholder="Contoh: Siti Rahmawati, S.Pd."
                            required
                            minlength="3"
                            maxlength="120"
                            autofocus
                            autocomplete="name"
                            :error="$errors->first('name')"
                            class="h-10 text-xs sm:text-sm"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-semibold text-on-surface mb-1.5">
                                Gmail Aktif <span class="text-error" aria-hidden="true">*</span>
                            </label>
                            <x-input
                                id="email"
                                name="email"
                                type="email"
                                :value="old('email')"
                                placeholder="nama@gmail.com"
                                required
                                maxlength="191"
                                autocomplete="email"
                                :error="$errors->first('email')"
                                class="h-10 text-xs sm:text-sm"
                            />
                        </div>

                        <div>
                            <label for="phone_wa" class="block text-xs font-semibold text-on-surface mb-1.5">
                                Nomor WhatsApp <span class="text-error" aria-hidden="true">*</span>
                            </label>
                            <x-input
                                id="phone_wa"
                                name="phone_wa"
                                type="tel"
                                :value="old('phone_wa')"
                                placeholder="081234567890"
                                required
                                maxlength="25"
                                pattern="^(\+?62|0)8[0-9\-\s]{7,16}$"
                                title="Gunakan format 08xx, 62xx, atau +62xx"
                                autocomplete="tel"
                                :error="$errors->first('phone_wa')"
                                class="h-10 text-xs sm:text-sm"
                            />
                            <p class="text-[11px] text-on-surface-variant mt-1">Format: 08xx, 62xx, atau +62xx</p>
                        </div>
                    </div>

                    <div>
                        <label for="requested_role" class="block text-xs font-semibold text-on-surface mb-1.5">
                            Peran yang Diajukan <span class="text-error" aria-hidden="true">*</span>
                        </label>
                        <select
                            id="requested_role"
                            name="requested_role"
                            required
                            class="w-full h-10 px-3 rounded-xl text-xs sm:text-sm bg-surface-lowest text-on-surface border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                        >
                            <option value="" disabled {{ old('requested_role') ? '' : 'selected' }}>Pilih peran Anda di sekolah</option>
                            @foreach($publicRoles as $roleOption)
                                <option value="{{ $roleOption->value }}" {{ old('requested_role') === $roleOption->value ? 'selected' : '' }}>
                                    {{ $roleOption->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('requested_role')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-on-surface mb-1.5">
                                Kata Sandi <span class="text-error" aria-hidden="true">*</span>
                            </label>
                            <x-input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Minimal 8 karakter"
                                required
                                minlength="8"
                                maxlength="100"
                                autocomplete="new-password"
                                :error="$errors->first('password')"
                                class="h-10 text-xs sm:text-sm"
                            />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-on-surface mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-error" aria-hidden="true">*</span>
                            </label>
                            <x-input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Ulangi kata sandi"
                                required
                                minlength="8"
                                maxlength="100"
                                autocomplete="new-password"
                                class="h-10 text-xs sm:text-sm"
                            />
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-button type="submit" variant="primary" size="md" class="w-full h-10 text-sm font-bold shadow-sm">
                            Ajukan Pendaftaran Akun
                        </x-button>
                    </div>
                </form>

                <div class="mt-5 pt-4 border-t border-border-subtle text-center text-xs text-on-surface-variant">
                    Sudah memiliki akun aktif?
                    <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">
                        Masuk ke Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

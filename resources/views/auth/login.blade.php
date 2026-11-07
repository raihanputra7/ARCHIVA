<x-guest-layout title="Portal Login — ARCHIVA SDN Jatipulo 07">
    <div class="h-screen w-screen overflow-hidden bg-surface flex items-center justify-center p-3 sm:p-4 lg:p-6" x-data="{
        email: '{{ old('email', '') }}',
        password: '',
        fillRole(roleEmail) {
            this.email = roleEmail;
            this.password = '';
            this.$nextTick(() => {
                document.getElementById('password')?.focus();
            });
        }
    }">
        <!-- Floating Theme Toggle for Login Page -->
        <div class="fixed top-3.5 right-3.5 z-50">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="p-2 rounded-xl bg-surface-lowest border border-border-subtle text-on-surface shadow-xs hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                :title="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                :aria-label="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
            >
                <svg x-show="$store.theme.dark" class="w-4 h-4 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                </svg>
                <svg x-show="!$store.theme.dark" class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>

        <div class="w-full max-w-5xl lg:max-w-6xl mx-auto my-auto">
            <div class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl overflow-hidden max-h-[90vh] lg:max-h-[82vh] flex flex-col justify-center">
                <div class="grid grid-cols-1 lg:grid-cols-12 h-full items-stretch">
                    
                    <!-- KOLOM KIRI: Panel Foto Gedung Sekolah SDN Jatipulo 07 (Rasio Landscape 5 dari 12) -->
                    <div class="relative bg-navy-dark min-h-[160px] lg:col-span-5 lg:min-h-full p-4 sm:p-5 lg:p-6 flex flex-col justify-start overflow-hidden order-last lg:order-first">
                        <!-- Foto Gedung dengan Overlay Halus Transparan -->
                        <div class="absolute inset-0 z-0">
                            <img
                                src="{{ asset('images/gedung-sdn07.jpg') }}"
                                alt="Gedung SDN Jatipulo 07 Jakarta"
                                class="w-full h-full object-cover object-center opacity-95 filter brightness-100 contrast-[1.02] scale-100 hover:scale-105 transition-transform duration-700 ease-out"
                            >
                            <!-- Overlay Gradient Halus Transparan: foto gedung tetap terang dan kontras dengan logo -->
                            <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 via-transparent to-navy-dark/25 pointer-events-none"></div>
                        </div>

                        <!-- Top: Logo Sekolah di Panel Putih Rapi (DESIGN_SYSTEM.md Bagian 1 Aturan 2) -->
                        <div class="relative z-10 flex items-start">
                            <x-logo variant="on-dark" size="sm" class="shadow-sm border border-white/80" />
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Form Login Compact (Rasio 7 dari 12) -->
                    <div class="p-5 sm:p-6 lg:px-8 lg:py-6 lg:col-span-7 flex flex-col justify-center bg-surface-lowest overflow-hidden">
                        <div>
                            <!-- Header Form -->
                            <div class="mb-2.5 lg:mb-3">
                                <div class="lg:hidden mb-2">
                                    <x-logo size="sm" />
                                </div>
                                <h2 class="text-xl sm:text-2xl font-extrabold text-on-surface tracking-tight leading-tight">
                                    Selamat Datang
                                </h2>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    Masuk ke akun persuratan & administrasi sekolah Anda
                                </p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-2.5 p-2.5 rounded-xl bg-success-container text-on-success-container border border-success/30 text-xs font-medium flex items-center gap-2" role="status">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <!-- Error Alert Umum -->
                            @if ($errors->any())
                                <div class="mb-2.5 p-2.5 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-medium" role="alert">
                                    <div class="flex items-center gap-1.5 font-bold mb-0.5">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <span>Gagal masuk ke sistem</span>
                                    </div>
                                    <p>{{ $errors->first() }}</p>
                                </div>
                            @endif

                            <!-- Form Autentikasi -->
                            <form method="POST" action="{{ route('login') }}" class="space-y-2.5 lg:space-y-3">
                                @csrf

                                <!-- Email / NIP -->
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-on-surface mb-1">
                                        Email atau NIP <span class="text-error" aria-hidden="true">*</span>
                                    </label>
                                    <x-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        placeholder="nama@archiva.test"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        x-model="email"
                                        :error="$errors->first('email')"
                                        class="!h-9 !py-1.5 text-xs sm:text-sm"
                                    />
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="password" class="block text-xs font-semibold text-on-surface">
                                            Kata Sandi <span class="text-error" aria-hidden="true">*</span>
                                        </label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-[11px] text-primary hover:underline font-semibold">
                                                Lupa kata sandi?
                                            </a>
                                        @endif
                                    </div>
                                    <x-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        placeholder="Masukkan kata sandi akun"
                                        required
                                        autocomplete="current-password"
                                        x-model="password"
                                        :error="$errors->first('password')"
                                        class="!h-9 !py-1.5 text-xs sm:text-sm"
                                    />
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center justify-between pt-0.5">
                                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                                        <input
                                            id="remember_me"
                                            type="checkbox"
                                            name="remember"
                                            class="w-3.5 h-3.5 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-2"
                                        >
                                        <span class="text-[11px] text-on-surface-variant font-medium">Ingat saya di perangkat ini</span>
                                    </label>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="pt-0.5">
                                    <x-button type="submit" variant="primary" size="md" class="w-full !py-2 text-xs sm:text-sm font-bold">
                                        <span>Masuk ke Sistem</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </x-button>
                                </div>
                            </form>

                            <!-- Quick Role Select Demo Chips (Hanya di local untuk memudahkan pengujian) -->
                            @if (app()->environment('local'))
                                <div class="mt-2.5 pt-2 border-t border-border-subtle">
                                    <p class="text-[9.5px] font-bold uppercase tracking-wider text-outline mb-1.5">
                                        Masuk Cepat Uji Coba Peran (Lokal)
                                    </p>
                                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-1.5">
                                        <button
                                            type="button"
                                            @click="fillRole('admin@archiva.test')"
                                            class="px-2 py-1 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[10.5px] font-medium text-on-surface-variant text-center transition-colors truncate"
                                        >
                                            🛡️ Admin
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('kepsek@archiva.test')"
                                            class="px-2 py-1 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[10.5px] font-medium text-on-surface-variant text-center transition-colors truncate"
                                        >
                                            🏛️ Kepsek
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('tu@archiva.test')"
                                            class="px-2 py-1 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[10.5px] font-medium text-on-surface-variant text-center transition-colors truncate"
                                        >
                                            📝 TU
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('guru@archiva.test')"
                                            class="px-2 py-1 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[10.5px] font-medium text-on-surface-variant text-center transition-colors truncate"
                                        >
                                            📚 Guru
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('tendik@archiva.test')"
                                            class="px-2 py-1 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[10.5px] font-medium text-on-surface-variant text-center transition-colors truncate"
                                        >
                                            🔧 Tendik
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Legal -->
                        <div class="mt-2.5 pt-2 border-t border-border-subtle text-center">
                            <p class="text-[10px] text-on-surface-variant leading-relaxed">
                                Dengan masuk ke sistem, Anda menyetujui ketentuan kedinasan dan kebijakan administrasi persuratan internal SDN Jatipulo 07 Jakarta.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

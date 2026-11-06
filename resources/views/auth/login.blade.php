<x-guest-layout title="Portal Login — ARCHIVA SDN Jatipulo 07">
    <div class="min-h-screen bg-surface flex items-center justify-center p-4 sm:p-6 lg:p-8" x-data="{
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
        <div class="fixed top-4 right-4 z-50">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="p-2.5 rounded-xl bg-surface-lowest border border-border-subtle text-on-surface shadow-sm hover:bg-surface-container transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                :title="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                :aria-label="$store.theme.dark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
            >
                <svg x-show="$store.theme.dark" class="w-5 h-5 text-gold-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                </svg>
                <svg x-show="!$store.theme.dark" class="w-5 h-5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                </svg>
            </button>
        </div>

        <div class="w-full max-w-5xl mx-auto">
            <div class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    
                    <!-- KOLOM KIRI: Panel Navy & Foto Gedung Sekolah -->
                    <div class="relative bg-navy-dark min-h-[300px] lg:min-h-[640px] p-6 sm:p-8 lg:p-10 flex flex-col justify-between overflow-hidden order-last lg:order-first">
                        <!-- Foto Gedung dengan Overlay Navy Gelap -->
                        <div class="absolute inset-0 z-0">
                            <img
                                src="{{ asset('images/gedung-sdn07.jpg') }}"
                                alt="Gedung SDN Jatipulo 07 Jakarta"
                                class="w-full h-full object-cover opacity-35 filter brightness-75 scale-105 hover:scale-100 transition-transform duration-700 ease-out"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-navy-dark via-navy-dark/70 to-navy-dark/90"></div>
                        </div>

                        <!-- Top: Logo Sekolah di Panel Putih (Wajib DESIGN_SYSTEM.md Bagian 1 Aturan 2) -->
                        <div class="relative z-10 flex items-start">
                            <x-logo variant="on-dark" size="md" />
                        </div>

                        <!-- Bottom: Slogan & Deskripsi Sekolah -->
                        <div class="relative z-10 space-y-3 mt-12 lg:mt-0">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-lowest/15 backdrop-blur-sm border border-white/20 text-xs font-semibold text-white">
                                <span class="w-2 h-2 rounded-full bg-gold"></span>
                                <span>Gedung 4 Lantai • Sarana Pendidikan Terpadu</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                                Sistem Administrasi Persuratan Terpadu
                            </h1>
                            <p class="text-sm text-gray-300 leading-relaxed max-w-md">
                                Mendukung tata kelola persuratan dinas, disposisi pimpinan, dan pengarsipan digital SDN Jatipulo 07 Jakarta Barat secara transparan dan terintegrasi.
                            </p>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Form Login -->
                    <div class="p-6 sm:p-10 lg:p-12 flex flex-col justify-between bg-surface-lowest">
                        <div>
                            <!-- Header Form -->
                            <div class="mb-8">
                                <div class="lg:hidden mb-6">
                                    <x-logo size="md" />
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight">
                                    Selamat Datang
                                </h2>
                                <p class="text-sm text-on-surface-variant mt-1.5">
                                    Masuk ke akun persuratan & administrasi sekolah Anda
                                </p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-5 p-3.5 rounded-xl bg-success-container text-on-success-container border border-success/30 text-xs font-medium flex items-center gap-2" role="status">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <!-- Error Alert Umum -->
                            @if ($errors->any())
                                <div class="mb-5 p-3.5 rounded-xl bg-error-container text-on-error-container border border-error/30 text-xs font-medium" role="alert">
                                    <div class="flex items-center gap-2 font-bold mb-1">
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
                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                @csrf

                                <!-- Email / NIP -->
                                <div>
                                    <x-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        label="Email atau NIP"
                                        placeholder="nama@archiva.test"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        x-model="email"
                                        :error="$errors->first('email')"
                                    />
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="password" class="block text-sm font-semibold text-on-surface">
                                            Kata Sandi <span class="text-error" aria-hidden="true">*</span>
                                        </label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:underline font-semibold">
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
                                    />
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center justify-between pt-1">
                                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                                        <input
                                            id="remember_me"
                                            type="checkbox"
                                            name="remember"
                                            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary focus:ring-offset-2"
                                        >
                                        <span class="text-xs text-on-surface-variant font-medium">Ingat saya di perangkat ini</span>
                                    </label>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="pt-2">
                                    <x-button type="submit" variant="primary" size="lg" class="w-full">
                                        <span>Masuk ke Sistem</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </x-button>
                                </div>
                            </form>

                            <!-- Quick Role Select Demo Chips (Hanya di local untuk memudahkan pengujian) -->
                            @if (app()->environment('local'))
                                <div class="mt-6 pt-5 border-t border-border-subtle">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-outline mb-2.5">
                                        Masuk Cepat Uji Coba Peran (Lokal)
                                    </p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                                        <button
                                            type="button"
                                            @click="fillRole('admin@archiva.test')"
                                            class="px-2.5 py-1.5 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[11px] font-semibold text-on-surface-variant text-left transition-colors truncate"
                                        >
                                            🛡️ Admin
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('kepsek@archiva.test')"
                                            class="px-2.5 py-1.5 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[11px] font-semibold text-on-surface-variant text-left transition-colors truncate"
                                        >
                                            🏛️ Kepsek
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('tu@archiva.test')"
                                            class="px-2.5 py-1.5 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[11px] font-semibold text-on-surface-variant text-left transition-colors truncate"
                                        >
                                            📝 Tata Usaha
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('guru@archiva.test')"
                                            class="px-2.5 py-1.5 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[11px] font-semibold text-on-surface-variant text-left transition-colors truncate"
                                        >
                                            📚 Guru
                                        </button>
                                        <button
                                            type="button"
                                            @click="fillRole('tendik@archiva.test')"
                                            class="col-span-2 sm:col-span-1 px-2.5 py-1.5 rounded-lg border border-border-subtle bg-surface-container-low hover:bg-primary-container hover:text-primary text-[11px] font-semibold text-on-surface-variant text-left transition-colors truncate"
                                        >
                                            🔧 Tendik
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Legal -->
                        <div class="mt-8 pt-4 border-t border-border-subtle text-center">
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Dengan masuk ke sistem, Anda menyetujui ketentuan kedinasan dan kebijakan administrasi persuratan internal SDN Jatipulo 07 Jakarta.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

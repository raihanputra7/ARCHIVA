<x-guest-layout title="Pendaftaran Diterima — ARCHIVA SDN Jatipulo 07">
    <div class="min-h-screen w-full bg-surface flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-lg mx-auto">
            <div class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl p-6 sm:p-8 space-y-5">
                <div class="flex items-center justify-between">
                    <x-logo size="sm" />
                </div>

                <div class="p-4 rounded-2xl bg-success-container text-on-success-container border border-success/30">
                    <h1 class="text-lg font-extrabold tracking-tight">
                        Pendaftaran Diterima, Menunggu Persetujuan Admin
                    </h1>
                    <p class="text-xs mt-1.5 leading-relaxed">
                        Kami telah mengirimkan tautan verifikasi ke alamat email Anda.
                        <strong>Harap klik tautan verifikasi tersebut dalam 24 jam</strong> agar pendaftaran Anda dapat diproses dan disetujui oleh Administrator.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-3 rounded-xl bg-primary-container text-primary border border-primary/20 text-xs font-medium" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="pt-2 border-t border-border-subtle">
                    <p class="text-xs font-semibold text-on-surface mb-2">
                        Belum menerima email verifikasi?
                    </p>
                    <form method="POST" action="{{ route('register.resend-verification') }}" class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        <x-input
                            name="email"
                            type="email"
                            :value="old('email', session('registered_email', ''))"
                            placeholder="Masukkan email pendaftaran Anda"
                            required
                            class="h-10 text-xs flex-1"
                        />
                        <x-button type="submit" variant="primary" size="md" class="h-10 text-xs whitespace-nowrap">
                            Kirim Ulang Verifikasi
                        </x-button>
                    </form>
                    @error('email')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-primary hover:underline">
                        &larr; Kembali ke Halaman Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


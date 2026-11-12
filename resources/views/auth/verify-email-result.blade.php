<x-guest-layout title="Verifikasi Email — ARCHIVA SDN Jatipulo 07">
    <div class="min-h-screen w-full bg-surface flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-lg mx-auto">
            <div class="bg-surface-lowest border border-border-subtle rounded-3xl shadow-xl p-6 sm:p-8 space-y-5">
                <x-logo size="sm" />

                @if ($success)
                    <div class="p-4 rounded-2xl bg-success-container text-on-success-container border border-success/30">
                        <h1 class="text-lg font-extrabold tracking-tight">Email Berhasil Diverifikasi</h1>
                        <p class="text-xs mt-1.5 leading-relaxed">{{ $message }}</p>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-error-container text-on-error-container border border-error/30">
                        <h1 class="text-lg font-extrabold tracking-tight">Verifikasi Tidak Dapat Diproses</h1>
                        <p class="text-xs mt-1.5 leading-relaxed">{{ $message }}</p>
                    </div>

                    <div class="pt-2 border-t border-border-subtle">
                        <p class="text-xs font-semibold text-on-surface mb-2">
                            Kirim ulang tautan verifikasi baru:
                        </p>
                        <form method="POST" action="{{ route('register.resend-verification') }}" class="flex flex-col sm:flex-row gap-2">
                            @csrf
                            <x-input
                                name="email"
                                type="email"
                                placeholder="Masukkan email pendaftaran Anda"
                                required
                                class="h-10 text-xs flex-1"
                            />
                            <x-button type="submit" variant="primary" size="md" class="h-10 text-xs whitespace-nowrap">
                                Kirim Ulang
                            </x-button>
                        </form>
                    </div>
                @endif

                <div class="pt-2 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-primary hover:underline">
                        &larr; Masuk ke Portal ARCHIVA
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


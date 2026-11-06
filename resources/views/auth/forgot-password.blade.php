<x-guest-layout title="Lupa Kata Sandi — ARCHIVA SDN Jatipulo 07">
    <div class="min-h-screen bg-surface flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-md">
            <x-card class="space-y-6">
                <div class="text-center space-y-2">
                    <x-logo size="md" class="justify-center" />
                    <h2 class="text-xl font-bold text-on-surface pt-2">Atur Ulang Kata Sandi</h2>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Masukkan alamat email kedinasan Anda. Tautan pemulihan kata sandi akan dikirimkan untuk mengatur ulang kata sandi baru.
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="p-3.5 rounded-xl bg-success-container text-on-success-container border border-success/30 text-xs font-medium" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input
                            id="email"
                            type="email"
                            name="email"
                            label="Alamat Email"
                            placeholder="nama@archiva.test"
                            :value="old('email')"
                            required
                            autofocus
                            :error="$errors->first('email')"
                        />
                    </div>

                    <div class="pt-2 flex flex-col gap-2">
                        <x-button type="submit" variant="primary" class="w-full">
                            Kirim Tautan Atur Ulang Sandi
                        </x-button>
                        <a href="{{ route('login') }}" class="text-xs text-center text-primary hover:underline font-semibold py-1">
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-guest-layout>

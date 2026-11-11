<section class="space-y-4">
    <p class="text-sm text-on-surface-variant leading-relaxed">
        Setelah akun Anda dihapus, seluruh data dan akses yang terkait akan dihapus secara permanen. Pastikan Anda telah mencadangkan atau mengunduh informasi penting sebelum melanjutkan.
    </p>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-on-surface">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-1.5 text-sm text-on-surface-variant leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun secara permanen.
            </p>

            <div class="mt-5">
                <x-input-label for="delete_account_password" value="Kata Sandi" class="sr-only" />

                <x-text-input
                    id="delete_account_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full sm:w-3/4"
                    placeholder="Masukkan kata sandi Anda"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button>
                    Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

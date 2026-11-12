<div
    x-data
    x-show="$store.confirm.open"
    x-cloak
    @keydown.escape.window="$store.confirm.cancel()"
    class="fixed inset-0 z-[75] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirm-modal-title"
>
    <div
        x-show="$store.confirm.open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.confirm.cancel()"
        class="fixed inset-0 z-0 bg-navy-dark/60 backdrop-blur-[2px]"
    ></div>

    <div
        x-show="$store.confirm.open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative z-10 w-full max-w-md bg-surface-lowest border border-border-subtle rounded-2xl shadow-xl p-6 space-y-4"
    >
        <div class="space-y-1.5">
            <h3 id="confirm-modal-title" class="text-base font-bold text-on-surface" x-text="$store.confirm.title"></h3>
            <p class="text-xs text-on-surface-variant leading-relaxed" x-text="$store.confirm.message"></p>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-2">
            <button
                type="button"
                @click="$store.confirm.cancel()"
                class="px-4 py-2 rounded-lg border border-border-subtle bg-surface-lowest text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors cursor-pointer"
                x-text="$store.confirm.cancelText"
            ></button>
            <button
                type="button"
                @click="$store.confirm.proceed()"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                :class="$store.confirm.variant === 'danger' ? 'bg-error text-white hover:opacity-90' : 'bg-primary text-on-primary hover:bg-primary/90'"
                x-text="$store.confirm.confirmText"
            ></button>
        </div>
    </div>
</div>


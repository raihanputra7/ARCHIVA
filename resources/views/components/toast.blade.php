<div
    x-data
    x-init="
        @if(session('status'))
            $store.toast.push('success', @js(session('status')));
        @endif
        @if(session('error'))
            $store.toast.push('error', @js(session('error')));
        @endif
        @if(session('warning'))
            $store.toast.push('warning', @js(session('warning')));
        @endif
    "
    @toast.window="$store.toast.push($event.detail?.type || 'info', $event.detail?.message || '', $event.detail?.duration || 4000)"
    class="fixed bottom-5 right-5 z-[80] flex flex-col gap-2.5 max-w-sm w-[calc(100%-2.5rem)] pointer-events-none"
    aria-live="polite"
>
    <template x-for="item in $store.toast.items" :key="item.id">
        <div
            x-show="item.visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-start gap-2.5 p-3.5 rounded-2xl border shadow-lg text-xs font-semibold"
            :class="{
                'bg-success-container text-on-success-container border-success/30': item.type === 'success',
                'bg-error-container text-on-error-container border-error/30': item.type === 'error',
                'bg-warning-container text-on-warning-container border-warning/30': item.type === 'warning',
                'bg-surface-lowest text-on-surface border-border-subtle': item.type === 'info'
            }"
            role="status"
        >
            <span class="flex-1 leading-relaxed" x-text="item.message"></span>
            <button
                type="button"
                @click="$store.toast.dismiss(item.id)"
                class="p-0.5 rounded hover:opacity-75 cursor-pointer"
                aria-label="Tutup notifikasi"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>


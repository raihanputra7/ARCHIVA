@props([
    'id' => 'berkas',
    'name' => 'berkas',
    'accept' => '.pdf,.jpg,.jpeg,.png',
    'required' => false,
    'hint' => 'Format PDF, JPG, atau PNG (maks. 5 MB)',
])

<div
    x-data="{
        fileName: '',
        fileSize: '',
        progress: 0,
        uploading: false,
        ready: false,
        timer: null,
        handleFile(event) {
            const file = event.target.files[0];
            if (!file) {
                this.reset();
                return;
            }
            this.fileName = file.name;
            this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            this.uploading = true;
            this.ready = false;
            this.progress = 15;
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.progress < 95) {
                    this.progress += 20;
                } else {
                    this.progress = 100;
                    this.uploading = false;
                    this.ready = true;
                    clearInterval(this.timer);
                }
            }, 90);
        },
        reset() {
            clearInterval(this.timer);
            this.fileName = '';
            this.fileSize = '';
            this.progress = 0;
            this.uploading = false;
            this.ready = false;
        }
    }"
    class="space-y-2"
>
    <input
        id="{{ $id }}"
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        @if($required) required @endif
        @change="handleFile($event)"
        {{ $attributes->merge(['class' => 'w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-container file:text-primary hover:file:bg-primary-container/80 cursor-pointer']) }}
    >

    <p class="text-[11px] text-outline">{{ $hint }}</p>

    <div x-show="fileName !== ''" x-cloak class="p-2.5 rounded-xl border border-border-subtle bg-surface-container-low/60 space-y-1.5">
        <div class="flex items-center justify-between gap-2 text-xs">
            <span class="font-semibold text-on-surface truncate" x-text="fileName"></span>
            <span class="text-[11px] text-on-surface-variant tabular-nums flex-shrink-0" x-text="fileSize + ' • ' + progress + '%'"></span>
        </div>
        <div class="w-full h-1.5 rounded-full bg-surface-container overflow-hidden">
            <div
                class="h-full rounded-full transition-all duration-150"
                :class="ready ? 'bg-success' : 'bg-primary'"
                :style="`width: ${progress}%`"
            ></div>
        </div>
    </div>
</div>


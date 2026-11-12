<div
    x-data="{
        active: false,
        progress: 0,
        timer: null,
        start() {
            this.active = true;
            this.progress = 15;
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.progress < 85) {
                    this.progress += Math.max(2, (85 - this.progress) * 0.15);
                }
            }, 120);
        },
        finish() {
            this.progress = 100;
            clearInterval(this.timer);
            setTimeout(() => {
                this.active = false;
                this.progress = 0;
            }, 250);
        }
    }"
    x-init="
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href') || '';
            if (href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || e.ctrlKey || e.metaKey || e.shiftKey) return;
            if (link.origin === window.location.origin) {
                start();
            }
        });
        document.addEventListener('submit', (e) => {
            if (!e.defaultPrevented) start();
        });
        window.addEventListener('pageshow', () => finish());
    "
    x-show="active"
    x-cloak
    class="fixed top-0 left-0 right-0 z-[70] h-1 bg-transparent pointer-events-none"
    aria-hidden="true"
>
    <div
        class="h-full bg-gradient-to-r from-primary via-secondary to-primary transition-all duration-150 ease-out shadow-xs"
        :style="`width: ${progress}%`"
    ></div>
</div>


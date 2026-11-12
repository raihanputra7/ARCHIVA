import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Chart = Chart;

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        dark: localStorage.getItem('archiva_theme') === 'dark' || (!('archiva_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        init() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('archiva_theme', this.dark ? 'dark' : 'light');
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
    });

    Alpine.store('toast', {
        items: [],
        nextId: 1,
        push(type, message, duration = 4000) {
            if (!message) return;
            const id = this.nextId++;
            this.items.push({ id, type: type || 'info', message, visible: true });
            setTimeout(() => this.dismiss(id), duration);
        },
        dismiss(id) {
            const item = this.items.find((i) => i.id === id);
            if (item) item.visible = false;
            setTimeout(() => {
                this.items = this.items.filter((i) => i.id !== id);
            }, 250);
        },
    });

    Alpine.store('confirm', {
        open: false,
        title: 'Konfirmasi Tindakan',
        message: 'Apakah Anda yakin ingin melanjutkan?',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        variant: 'primary',
        targetForm: null,
        callback: null,
        ask({ title, message, confirmText = 'Ya, Lanjutkan', cancelText = 'Batal', variant = 'primary', form = null, onConfirm = null }) {
            this.title = title || 'Konfirmasi Tindakan';
            this.message = message || 'Apakah Anda yakin ingin melanjutkan?';
            this.confirmText = confirmText;
            this.cancelText = cancelText;
            this.variant = variant;
            this.targetForm = form;
            this.callback = onConfirm;
            this.open = true;
        },
        proceed() {
            this.open = false;
            if (this.targetForm) {
                this.targetForm.submit();
            } else if (typeof this.callback === 'function') {
                this.callback();
            }
            this.targetForm = null;
            this.callback = null;
        },
        cancel() {
            this.open = false;
            this.targetForm = null;
            this.callback = null;
        },
    });

    Alpine.store('notifications', {
        supported: typeof window !== 'undefined' && 'Notification' in window,
        permission: typeof window !== 'undefined' && 'Notification' in window ? Notification.permission : 'denied',
        lastCount: null,
        pollUrl: null,
        initPolling(url, initialCount) {
            this.pollUrl = url;
            this.lastCount = initialCount;
            if (!this.pollUrl) return;
            setInterval(() => this.check(), 30000);
        },
        async requestPermission() {
            if (!this.supported) {
                Alpine.store('toast').push('warning', 'Browser Anda tidak mendukung notifikasi desktop.');
                return;
            }
            const result = await Notification.requestPermission();
            this.permission = result;
            if (result === 'granted') {
                Alpine.store('toast').push('success', 'Notifikasi browser berhasil diaktifkan.');
            } else if (result === 'denied') {
                Alpine.store('toast').push('warning', 'Izin notifikasi ditolak di pengaturan browser.');
            }
        },
        async check() {
            if (!this.pollUrl || !this.supported || this.permission !== 'granted') return;
            try {
                const res = await fetch(this.pollUrl, { headers: { Accept: 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();
                if (typeof data.count === 'number' && this.lastCount !== null && data.count > this.lastCount) {
                    if (document.hidden) {
                        new Notification('ARCHIVA — SDN Jatipulo 07', {
                            body: data.message || 'Ada pembaruan disposisi atau surat baru untuk Anda.',
                            icon: '/images/logo-sdn-jatipulo07.png',
                        });
                    } else {
                        Alpine.store('toast').push('info', data.message || 'Ada pembaruan disposisi atau surat baru.');
                    }
                }
                if (typeof data.count === 'number') {
                    this.lastCount = data.count;
                }
            } catch {
                // Abaikan error jaringan sementara agar tidak mengganggu pengguna
            }
        },
    });
});

window.Alpine = Alpine;

Alpine.start();

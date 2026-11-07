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
        }
    });
});

window.Alpine = Alpine;

Alpine.start();

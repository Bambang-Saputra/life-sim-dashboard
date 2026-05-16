import './bootstrap';

// ⚠️ JANGAN import Alpine dari 'alpinejs' — Livewire 4 sudah bundle Alpine sendiri.
// Import lagi = 2 instance = wire:click & @entangle rusak.
// Daftarkan plugin & component lewat event 'alpine:init' supaya pakai Alpine-nya Livewire.

import Persist  from '@alpinejs/persist';
import Focus    from '@alpinejs/focus';
import Collapse from '@alpinejs/collapse';

// ── Chart.js ──
import {
    Chart,
    BarController, BarElement,
    LineController, LineElement,
    DoughnutController, ArcElement,
    PointElement, CategoryScale, LinearScale,
    Tooltip, Legend, Filler,
} from 'chart.js';

Chart.register(
    BarController, BarElement,
    LineController, LineElement,
    DoughnutController, ArcElement,
    PointElement, CategoryScale, LinearScale,
    Tooltip, Legend, Filler,
);
window.Chart = Chart;

// ── Alpine setup (runs once when Livewire's Alpine boots) ──
document.addEventListener('alpine:init', () => {

    // ─── Plugins ───
    Alpine.plugin(Persist);
    Alpine.plugin(Focus);
    Alpine.plugin(Collapse);

    // ─── Quest Alarm Component ───
    Alpine.data('questAlarm', (dueTime) => ({
        timeLeft: '',
        urgency: 'normal',
        init() {
            if (!dueTime) return;
            this.update();
            this._timer = setInterval(() => this.update(), 60000);
        },
        destroy() { clearInterval(this._timer); },
        update() {
            const diff = new Date(dueTime) - new Date();
            if (diff <= 0) { this.timeLeft = 'OVERDUE'; this.urgency = 'critical'; return; }
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const d = Math.floor(diff / 86400000);
            if (diff < 3600000)       { this.urgency = 'critical'; this.timeLeft = m + 'm left'; }
            else if (diff < 86400000) { this.urgency = 'warning';  this.timeLeft = h + 'h ' + m + 'm'; }
            else                      { this.urgency = 'normal';   this.timeLeft = d + 'd left'; }
        }
    }));

    // ─── Chart Factories ───
    const baseLineOpts = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: 'Inter', size: 11 }, color: '#5C4632' } } },
        scales: {
            x: { ticks: { color: '#83644A', font: { family: 'Inter', size: 10 } }, grid: { color: 'rgba(212,163,115,0.15)' } },
            y: { ticks: { color: '#83644A', font: { family: 'Inter', size: 10 } }, grid: { color: 'rgba(212,163,115,0.15)' } },
        },
        elements: { line: { tension: 0.35, borderWidth: 2 }, point: { radius: 3, hoverRadius: 5 } },
    };

    Alpine.data('lineChart', (config) => ({
        chart: null,
        init() {
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: config.data,
                options: Object.assign({}, baseLineOpts, config.options || {}),
            });
        },
        destroy() { if (this.chart) this.chart.destroy(); },
    }));

    const baseBarOpts = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: 'Inter', size: 11 }, color: '#5C4632' } } },
        scales: {
            x: { ticks: { color: '#83644A', font: { family: 'Inter', size: 10 } }, grid: { display: false } },
            y: { ticks: { color: '#83644A', font: { family: 'Inter', size: 10 } }, grid: { color: 'rgba(212,163,115,0.15)' } },
        },
        borderRadius: 4,
    };

    Alpine.data('barChart', (config) => ({
        chart: null,
        init() {
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'bar',
                data: config.data,
                options: Object.assign({}, baseBarOpts, config.options || {}),
            });
        },
        destroy() { if (this.chart) this.chart.destroy(); },
    }));

    Alpine.data('doughnutChart', (config) => ({
        chart: null,
        init() {
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'doughnut',
                data: config.data,
                options: Object.assign({
                    responsive: true, maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 }, color: '#5C4632', padding: 12 } },
                    },
                }, config.options || {}),
            });
        },
        destroy() { if (this.chart) this.chart.destroy(); },
    }));
});

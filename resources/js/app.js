import './bootstrap';

// ⚠️ JANGAN import Alpine dari 'alpinejs' — Livewire 4 sudah bundle Alpine sendiri.
// Import lagi = 2 instance = wire:click & @entangle rusak.
//
// Mode "manual bundle": import Livewire + Alpine-nya Livewire, daftarkan plugin &
// component DULU, baru Livewire.start() (lihat paling bawah). Sebelumnya registrasi
// dilakukan lewat event 'alpine:init', tapi karena app.js di-load sebagai module
// (deferred) sementara @livewireScripts memuat livewire.js classic yang start Alpine
// saat parsing, 'alpine:init' sudah lewat sebelum app.js jalan → x-data="barChart(...)"
// dkk error "barChart is not defined" dan chart tidak pernah ter-render.
// Layout pakai @livewireScriptConfig (bukan @livewireScripts) agar tidak dobel boot.
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Catatan: Persist, Focus, & Collapse sudah dibundel + didaftarkan oleh Livewire
// sendiri (lihat Livewire.start()). Mendaftarkan ulang di sini = error
// "Cannot redefine property: $persist" yang menggagalkan boot Alpine, jadi
// jangan import/registrasi plugin-plugin itu lagi.

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

// ─── Pixel Scene Component ───
// Interaktivitas bersama untuk scene pixel-art di page hero:
// - Parallax mouse: set --px/--py (-1..1) di root scene, layer CSS
//   .par-far/.par-mid/.par-near translate dengan depth berbeda.
// - pop(): state "burst" sekali jalan untuk reaksi klik
//   (trofi meledak sparkle, brankas goyang + koin, TV menyala).
// Menghormati prefers-reduced-motion (parallax dimatikan).
Alpine.data('pixelScene', () => ({
    px: 0,
    py: 0,
    burst: false,
    _reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    onMove(event) {
        if (this._reduced) return;
        const rect = this.$el.getBoundingClientRect();
        this.px = +(((event.clientX - rect.left) / rect.width - 0.5) * 2).toFixed(3);
        this.py = +(((event.clientY - rect.top) / rect.height - 0.5) * 2).toFixed(3);
    },
    resetPointer() {
        this.px = 0;
        this.py = 0;
    },
    pop() {
        this.burst = true;
        setTimeout(() => (this.burst = false), 900);
    },
}));

// ── Boot ── (registrasi di atas dijamin selesai sebelum Alpine men-walk DOM)
Livewire.start();

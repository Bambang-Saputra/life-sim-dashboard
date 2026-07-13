<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Life-Sim Dashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body
    x-data="appTheme()"
    x-init="init()"
    :class="todClass"
    class="font-sans text-soil-dark min-h-screen"
>
    <nav class="main-nav">
        <div class="nav-inner">
            <a href="{{ route('dashboard') }}" class="brand-lockup">
                <span class="brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
                        <path d="M12 3v18M8 7c2 0 3 1 4 3-1 0-3-1-4-3zM16 7c-2 0-3 1-4 3 1 0 3-1 4-3zM8 11c2 0 3 1 4 3-1 0-3-1-4-3zM16 11c-2 0-3 1-4 3 1 0 3-1 4-3zM8 15c2 0 3 1 4 3-1 0-3-1-4-3zM16 15c-2 0-3 1-4 3 1 0 3-1 4-3z"
                              stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>
                    <span class="brand-title">Life-Sim</span>
                    <span class="brand-subtitle">Bambang Farm OS</span>
                </span>
            </a>

            @php
                $navItems = [
                    ['route' => 'dashboard',    'label' => 'Dashboard', 'short' => 'DB'],
                    ['route' => 'quests.index', 'label' => 'Quests',    'short' => 'Q'],
                    ['route' => 'finance.index','label' => 'Finance',   'short' => 'F'],
                    ['route' => 'library.index','label' => 'Library',   'short' => 'L'],
                ];
            @endphp

            <div class="hidden md:flex items-center gap-1.5">
                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ $active ? 'is-active' : '' }}">
                        <span class="nav-chip">{{ $item['short'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="md:hidden relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="nav-menu-button" aria-label="Open navigation">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="mobile-nav-menu">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="mobile-nav-link {{ $active ? 'is-active' : '' }}">
                            <span class="nav-chip">{{ $item['short'] }}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex-1"></div>

            {{-- Mute toggle sound FX --}}
            <button type="button" @click="toggleMute()"
                    class="hidden sm:flex items-center justify-center w-8 h-8 text-cream-light/70 hover:text-cream-light hover:bg-white/10 transition-colors"
                    style="border-radius: 6px;"
                    :title="muted ? 'Nyalakan suara' : 'Matikan suara'">
                <svg x-show="!muted" viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                    <path d="M11 5L6 9H3v6h3l5 4V5zM15 9a4 4 0 0 1 0 6M18 6.5a8 8 0 0 1 0 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg x-show="muted" viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                    <path d="M11 5L6 9H3v6h3l5 4V5zM22 9l-6 6M16 9l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="hidden lg:flex items-center gap-2.5 px-3 py-1.5 bg-white/10 border border-white/15 rounded-full">
                <svg x-show="isDaytime" class="w-4 h-4 text-corn sun-glow animate-sun-shine" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="4"/>
                    <g stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="12" y1="3" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="21"/>
                        <line x1="3" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="21" y2="12"/>
                        <line x1="5.6" y1="5.6" x2="7" y2="7"/><line x1="17" y1="17" x2="18.4" y2="18.4"/>
                        <line x1="5.6" y1="18.4" x2="7" y2="17"/><line x1="17" y1="7" x2="18.4" y2="5.6"/>
                    </g>
                </svg>
                <svg x-show="!isDaytime" class="w-4 h-4 text-sky-light moon-glow" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
                </svg>
                <span class="font-mono text-cream-light text-sm tabular-nums" x-text="time">--:--</span>
                <span class="font-pixel text-corn-light text-[8px]" x-text="period">--</span>
            </div>

            <div class="user-cluster">
                <div class="user-avatar">
                    <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="hidden sm:block leading-tight">
                    <p class="text-cream-light/90 text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-cream-light/45 text-[11px]">Personal dashboard</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="logout-button">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                            <path d="M15 12H3m12 0l-3-3m3 3l-3 3M9 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H9"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="app-main">
        {{ $slot }}
    </main>

    {{-- ── Banner unlock achievement (global) ── --}}
    <div x-show="achBanner"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="ach-banner"
         style="display: none;">
        <span class="ach-banner-icon" x-text="achBanner?.icon"></span>
        <span>
            <span class="font-pixel block text-corn-light" style="font-size: 8px;">ACHIEVEMENT UNLOCKED!</span>
            <span class="font-sans font-bold text-cream-light text-sm block mt-1" x-text="achBanner?.title"></span>
            <span class="font-sans text-cream-light/70 text-xs block" x-text="achBanner?.desc"></span>
        </span>
    </div>
    <div class="confetti-layer" x-ref="globalConfetti" aria-hidden="true"></div>

    @livewireScriptConfig

    <script>
    function appTheme() {
        return {
            time: '--:--', period: '--', isDaytime: true, todClass: 'tod-day', hour: 0,
            muted: localStorage.getItem('lifesim_muted') === '1',
            achBanner: null,
            _achQueue: [],
            _audioCtx: null,
            init() {
                this.update();
                setInterval(() => this.update(), 30000);
                setInterval(() => {
                    this.time = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
                }, 1000);

                // ── Sound FX hooks (event Livewire menggelembung ke window) ──
                window.addEventListener('quest-completed', () => this.playSfx('complete'));
                window.addEventListener('finance-entry-saved', () => this.playSfx('coin'));
                window.addEventListener('rating-stier', () => this.playSfx('fanfare'));
                window.addEventListener('bird-chirp', () => this.playSfx('chirp'));
                window.addEventListener('achievement-unlocked', (e) => {
                    const d = e.detail || {};
                    this._achQueue.push({ icon: d.icon || '🏆', title: d.title || 'Achievement', desc: d.desc || '' });
                    this.drainAchQueue();
                });
            },
            toggleMute() {
                this.muted = !this.muted;
                localStorage.setItem('lifesim_muted', this.muted ? '1' : '0');
                if (!this.muted) this.playSfx('coin');
            },
            drainAchQueue() {
                if (this.achBanner || this._achQueue.length === 0) return;
                this.achBanner = this._achQueue.shift();
                this.playSfx('unlock');
                this.dropConfetti();
                setTimeout(() => { this.achBanner = null; setTimeout(() => this.drainAchQueue(), 350); }, 3800);
            },
            dropConfetti() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                const layer = this.$refs.globalConfetti;
                if (!layer) return;
                const colors = ['#E5B567', '#BE546E', '#6BA368', '#77AADD', '#F1CC8E'];
                for (let i = 0; i < 24; i++) {
                    const p = document.createElement('i');
                    p.style.left = (Math.random() * 100) + '%';
                    p.style.background = colors[i % colors.length];
                    p.style.animationDuration = (0.9 + Math.random() * 0.9) + 's';
                    p.style.animationDelay = (Math.random() * 0.3) + 's';
                    layer.appendChild(p);
                    setTimeout(() => p.remove(), 2400);
                }
            },
            // ── Synth chiptune mini via Web Audio (tanpa file audio) ──
            playSfx(name) {
                if (this.muted) return;
                try {
                    this._audioCtx = this._audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                    const ctx = this._audioCtx;
                    if (ctx.state === 'suspended') ctx.resume();
                    const SONGS = {
                        coin:     [[988, 0, 0.07], [1319, 0.07, 0.18]],
                        complete: [[659, 0, 0.09], [784, 0.09, 0.09], [1047, 0.18, 0.22]],
                        fanfare:  [[523, 0, 0.11], [659, 0.11, 0.11], [784, 0.22, 0.11], [1047, 0.33, 0.3]],
                        unlock:   [[784, 0, 0.08], [988, 0.08, 0.08], [1175, 0.16, 0.08], [1568, 0.24, 0.26]],
                        chirp:    [[2637, 0, 0.06], [3520, 0.07, 0.14], [2794, 0.16, 0.26]],
                    };
                    (SONGS[name] || SONGS.coin).forEach(([freq, at, dur]) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'square';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0.06, ctx.currentTime + at);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + at + dur);
                        osc.connect(gain).connect(ctx.destination);
                        osc.start(ctx.currentTime + at);
                        osc.stop(ctx.currentTime + at + dur + 0.02);
                    });
                } catch (e) { /* audio tidak tersedia - abaikan */ }
            },
            update() {
                const now = new Date();
                this.hour = now.getHours();
                this.time = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
                this.isDaytime = this.hour >= 6 && this.hour < 18;
                if (this.hour >= 5 && this.hour < 10)       { this.period = 'PAGI';  this.todClass = 'tod-morning'; }
                else if (this.hour >= 10 && this.hour < 16) { this.period = 'SIANG'; this.todClass = 'tod-day'; }
                else if (this.hour >= 16 && this.hour < 19) { this.period = 'SORE';  this.todClass = 'tod-evening'; }
                else                                         { this.period = 'MALAM'; this.todClass = 'tod-night'; }
            }
        }
    }
    </script>
</body>
</html>

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

    @livewireScriptConfig

    <script>
    function appTheme() {
        return {
            time: '--:--', period: '--', isDaytime: true, todClass: 'tod-day', hour: 0,
            init() {
                this.update();
                setInterval(() => this.update(), 30000);
                setInterval(() => {
                    this.time = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
                }, 1000);
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

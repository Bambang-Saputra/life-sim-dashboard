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
        <div class="max-w-7xl mx-auto px-5 py-3 flex items-center gap-4">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2 no-underline whitespace-nowrap hover:opacity-90 transition-opacity">
                <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-corn">
                    <path d="M12 3v18M8 7c2 0 3 1 4 3-1 0-3-1-4-3zM16 7c-2 0-3 1-4 3 1 0 3-1 4-3zM8 11c2 0 3 1 4 3-1 0-3-1-4-3zM16 11c-2 0-3 1-4 3 1 0 3-1 4-3zM8 15c2 0 3 1 4 3-1 0-3-1-4-3zM16 15c-2 0-3 1-4 3 1 0 3-1 4-3z"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span class="font-pixel text-cream-light" style="font-size: 9px; letter-spacing: 0.05em;">LIFE-SIM</span>
            </a>

            <div class="w-px h-5 bg-cream-dark/20"></div>

            {{-- Nav Links --}}
            @php
                $navItems = [
                    ['route' => 'dashboard',    'label' => 'Dashboard', 'icon' => '🏡'],
                    ['route' => 'quests.index', 'label' => 'Quests',    'icon' => '⚔'],
                    ['route' => 'finance.index','label' => 'Finance',   'icon' => '💰'],
                    ['route' => 'library.index','label' => 'Library',   'icon' => '📚'],
                ];
            @endphp

            <div class="hidden md:flex items-center gap-1">
                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="font-sans text-sm px-3 py-1.5 no-underline transition-all duration-100
                              {{ $active
                                  ? 'bg-white/15 text-cream-light font-semibold'
                                  : 'text-cream-light/70 hover:text-cream-light hover:bg-white/10' }}"
                       style="border-radius: 6px;">
                        <span class="text-base">{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Mobile menu (dropdown) --}}
            <div class="md:hidden relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="text-cream-light p-1.5 hover:bg-white/10"
                        style="border-radius: 6px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute left-0 top-full mt-1 w-44 bg-white border border-cream-dark shadow-cozy-lg p-1.5 z-50"
                     style="border-radius: 6px;">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="block font-sans text-sm px-3 py-2 no-underline
                                  {{ $active ? 'bg-grass-light/40 text-grass-dark font-semibold' : 'text-soil-dark hover:bg-cream' }}"
                           style="border-radius: 4px;">
                            <span class="text-base">{{ $item['icon'] }}</span> {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- Live Clock with Sun/Moon --}}
            <div class="hidden lg:flex items-center gap-2.5 px-3 py-1.5 bg-white/10 border border-white/15"
                 style="border-radius: 999px;">
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

            {{-- User --}}
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-grass border border-grass-dark flex items-center justify-center"
                     style="border-radius: 50%;">
                    <span class="font-pixel text-white text-[8px]">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <span class="font-sans text-sm text-cream-light/85 hidden sm:block">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="ml-1">
                    @csrf
                    <button type="submit" title="Logout"
                            class="w-7 h-7 flex items-center justify-center text-cream-light/60 hover:text-berry-light hover:bg-white/10 transition-colors duration-100"
                            style="border-radius: 4px;">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                            <path d="M15 12H3m12 0l-3-3m3 3l-3 3M9 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H9"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        {{ $slot }}
    </main>

    @livewireScripts

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

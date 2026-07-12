<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Life-Sim Dashboard') }} · Cozy Life Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-soil-dark min-h-screen overflow-x-hidden">

    {{-- ─── DECORATIVE SKY BACKGROUND ─── --}}
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute inset-0"
             style="background: linear-gradient(180deg, #B5D9EC 0%, #DCE9CC 50%, #C9DDB6 75%, #8FBC8A 100%);"></div>

        {{-- Hills --}}
        <div class="absolute bottom-20 left-0 right-0">
            <div class="absolute" style="bottom: 0; left: 3%;  width: 280px; height: 100px; background: #4E7D4C; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
            <div class="absolute" style="bottom: 0; left: 35%; width: 360px; height: 130px; background: #5C8F58; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
            <div class="absolute" style="bottom: 0; right: 5%; width: 220px; height: 80px; background: #4E7D4C; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
        </div>

        {{-- Trees --}}
        <div class="absolute" style="bottom: 78px; left: 12%;">
            <div style="width: 12px; height: 32px; background: #5C4632; margin: 0 auto;"></div>
            <div style="width: 54px; height: 38px; background: #4E7D4C; margin-top: -4px;
                        box-shadow: 0 -8px 0 #4E7D4C, 8px 8px 0 -3px #5C8F58;"></div>
        </div>
        <div class="absolute" style="bottom: 75px; right: 14%;">
            <div style="width: 10px; height: 28px; background: #5C4632; margin: 0 auto;"></div>
            <div style="width: 44px; height: 32px; background: #4E7D4C; margin-top: -3px;"></div>
        </div>

        {{-- Ground --}}
        <div class="absolute bottom-0 left-0 right-0" style="height: 80px;
             background: repeating-linear-gradient(90deg, #6B4E32 0, #6B4E32 24px, #5C4632 24px, #5C4632 48px);
             border-top: 4px solid #4E7D4C;"></div>
    </div>

    {{-- ─── NAV ─── --}}
    <nav class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-5 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 no-underline">
            <div class="w-10 h-10 bg-grass-dark border-2 border-soil-dark flex items-center justify-center shadow-cozy"
                 style="border-radius: 8px;">
                <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-corn-light">
                    <path d="M12 3v18M8 7c2 0 3 1 4 3-1 0-3-1-4-3zM16 7c-2 0-3 1-4 3 1 0 3-1 4-3zM8 11c2 0 3 1 4 3-1 0-3-1-4-3zM16 11c-2 0-3 1-4 3 1 0 3-1 4-3zM8 15c2 0 3 1 4 3-1 0-3-1-4-3zM16 15c-2 0-3 1-4 3 1 0 3-1 4-3z"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="font-pixel text-soil-dark" style="font-size: 11px; letter-spacing: 0.05em;">LIFE-SIM</span>
        </a>

        @if (Route::has('login'))
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Go to Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    {{-- ─── HERO ─── --}}
    <section class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 pt-12 sm:pt-20 pb-12 text-center">

        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/80 border border-cream-dark mb-6"
             style="border-radius: 999px;">
            <span class="w-2 h-2 bg-grass rounded-full animate-pulse"></span>
            <span class="font-sans text-xs font-medium text-soil-dark">Cozy life management, made for you</span>
        </div>

        <h1 class="font-pixel text-soil-dark leading-relaxed mb-4"
            style="font-size: clamp(20px, 5vw, 36px); text-shadow: 3px 3px 0 rgba(255,255,255,0.6); letter-spacing: 0.05em;">
            LIFE-SIM<br>
            <span class="text-grass-dark">DASHBOARD</span>
        </h1>

        <p class="font-sans text-soil-dark text-base sm:text-lg max-w-xl mx-auto mb-8 leading-relaxed">
            Kelola hidupmu seperti main game pertanian.
            <span class="font-semibold">Quest harian</span>, <span class="font-semibold">catatan keuangan</span>,
            dan <span class="font-semibold">koleksi film/anime</span>. Semua dalam satu dashboard hangat.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary px-6 py-3 text-sm">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary px-6 py-3 text-sm">
                    Mulai Gratis Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-ghost px-6 py-3 text-sm">
                    Sudah punya akun? Login
                </a>
            @endauth
        </div>
    </section>

    {{-- ─── FEATURES ─── --}}
    <section class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 pb-32">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white/95 border-2 border-cream-dark p-5 shadow-cozy" style="border-radius: 8px;">
                <div class="w-10 h-10 bg-grass-light/40 border border-grass/40 flex items-center justify-center mb-3"
                     style="border-radius: 6px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-grass-dark">
                        <path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="font-pixel text-soil-dark mb-2" style="font-size: 9px; letter-spacing: 0.05em;">QUEST BOARD</h3>
                <p class="font-sans text-soil text-sm">To-do list dengan sistem XP. Naik level setiap selesai tugas, dengan notifikasi alarm dan prioritas.</p>
            </div>

            <div class="bg-white/95 border-2 border-cream-dark p-5 shadow-cozy" style="border-radius: 8px;">
                <div class="w-10 h-10 bg-corn-light/45 border border-corn/40 flex items-center justify-center mb-3"
                     style="border-radius: 6px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-corn-dark">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 6v12M9 9h4.5a2.5 2.5 0 0 1 0 5h-3a2.5 2.5 0 0 0 0 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3 class="font-pixel text-soil-dark mb-2" style="font-size: 9px; letter-spacing: 0.05em;">GOLD LEDGER</h3>
                <p class="font-sans text-soil text-sm">Catat income & expense harian. Lihat saldo per bulan dengan kategori yang mudah dilacak.</p>
            </div>

            <div class="bg-white/95 border-2 border-cream-dark p-5 shadow-cozy" style="border-radius: 8px;">
                <div class="w-10 h-10 bg-sky-light/40 border border-sky/40 flex items-center justify-center mb-3"
                     style="border-radius: 6px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-sky-dark">
                        <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11M9 4v6M15 4v6"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="font-pixel text-soil-dark mb-2" style="font-size: 9px; letter-spacing: 0.05em;">LIBRARY WING</h3>
                <p class="font-sans text-soil text-sm">Track film, TV, anime, dan manga. Search via TMDB & Jikan API, rating personal, status koleksi.</p>
            </div>

        </div>

        <p class="text-center font-sans text-soil-dark/60 text-xs mt-10">
            Built with Laravel · Livewire · Alpine.js · Tailwind CSS
        </p>
    </section>

</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Life-Sim Dashboard') }} · Cozy Life Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-soil-dark min-h-screen overflow-x-hidden">

    {{-- ─── LATAR PIXEL: langit berpita + gunung stair-stepped + tanah ─── --}}
    <div class="fixed inset-0 -z-10 pointer-events-none" aria-hidden="true">
        {{-- langit: 3 pita + dither di tiap sambungan --}}
        <div class="absolute inset-x-0" style="top:0; height:34vh; background:#8CB8DE;"></div>
        <div class="absolute inset-x-0" style="top:34vh; height:24vh; background:#A9CBE7;"></div>
        <div class="absolute inset-x-0" style="top:58vh; bottom:0; background:#C7DFF0;"></div>
        <div class="px-dither" style="top:calc(34vh - 4px); --da:#A9CBE7; --db:#8CB8DE;"></div>
        <div class="px-dither" style="top:calc(58vh - 4px); --da:#C7DFF0; --db:#A9CBE7;"></div>

        {{-- gunung stair-stepped di garis horizon --}}
        <div class="px-mtn" style="left:-40px; bottom:122px; width:240px; height:110px; background:#77A874; opacity:.75;"></div>
        <div class="px-mtn" style="left:22%; bottom:122px; width:320px; height:150px; background:#5C8F58; opacity:.8;"></div>
        <div class="px-mtn" style="right:16%; bottom:122px; width:200px; height:92px; background:#4E7D4C; opacity:.85;"></div>
        <div class="px-mtn" style="right:-40px; bottom:122px; width:280px; height:124px; background:#77A874; opacity:.75;"></div>

        {{-- rumput: 2 pita + dither --}}
        <div class="absolute inset-x-0" style="bottom:96px; height:26px; background:#8FBC8A;"></div>
        <div class="absolute inset-x-0" style="bottom:56px; height:40px; background:#6BA368;"></div>
        <div class="px-dither" style="bottom:92px; --da:#6BA368; --db:#8FBC8A;"></div>

        {{-- pohon pixel (batang + tajuk kotak berundak) --}}
        <div class="absolute" style="bottom:112px; left:10%;">
            <div style="width:10px; height:26px; background:#5C4632; margin:0 auto;"></div>
            <div style="width:44px; height:30px; background:#4E7D4C; margin-top:-4px;
                        box-shadow: 0 -10px 0 -6px #4E7D4C, 8px 6px 0 -4px #5C8F58, -8px 6px 0 -5px #5C8F58;"></div>
        </div>
        <div class="absolute" style="bottom:108px; right:12%;">
            <div style="width:8px; height:20px; background:#5C4632; margin:0 auto;"></div>
            <div style="width:34px; height:24px; background:#4E7D4C; margin-top:-3px;
                        box-shadow: 0 -8px 0 -5px #4E7D4C, 6px 5px 0 -4px #5C8F58;"></div>
        </div>

        {{-- tanah papan --}}
        <div class="absolute inset-x-0" style="bottom:54px; height:2px; background:#5C4632;"></div>
        <div class="absolute inset-x-0 bottom-0" style="height:54px; background:#83644A;
             background-image:repeating-linear-gradient(90deg, transparent 0 46px, #6E5138 46px 49px);"></div>
    </div>

    {{-- ─── NAV ─── --}}
    <nav class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-5 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 no-underline">
            <div class="w-10 h-10 bg-grass-dark border-2 border-soil-dark flex items-center justify-center shadow-cozy"
                 style="border-radius: 4px;">
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

        <span class="page-kicker mb-6">
            <span class="w-2 h-2 bg-grass rounded-full animate-pulse"></span>
            Cozy life management, made for you
        </span>

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
    <section class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 pb-40">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="panel">
                <div class="w-10 h-10 bg-grass-light/40 border border-grass/40 flex items-center justify-center mb-3"
                     style="border-radius: 4px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-grass-dark">
                        <path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="font-pixel text-soil-dark mb-2" style="font-size: 9px; letter-spacing: 0.05em;">QUEST BOARD</h3>
                <p class="font-sans text-soil text-sm">To-do list dengan sistem XP. Naik level setiap selesai tugas, dengan notifikasi alarm dan prioritas.</p>
            </div>

            <div class="panel">
                <div class="w-10 h-10 bg-corn-light/45 border border-corn/40 flex items-center justify-center mb-3"
                     style="border-radius: 4px;">
                    <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-corn-dark">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 6v12M9 9h4.5a2.5 2.5 0 0 1 0 5h-3a2.5 2.5 0 0 0 0 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3 class="font-pixel text-soil-dark mb-2" style="font-size: 9px; letter-spacing: 0.05em;">GOLD LEDGER</h3>
                <p class="font-sans text-soil text-sm">Catat income & expense harian. Lihat saldo per bulan dengan kategori yang mudah dilacak.</p>
            </div>

            <div class="panel">
                <div class="w-10 h-10 bg-sky-light/40 border border-sky/40 flex items-center justify-center mb-3"
                     style="border-radius: 4px;">
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

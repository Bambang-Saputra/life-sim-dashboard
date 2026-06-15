<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Life-Sim Dashboard') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-soil-dark min-h-screen flex flex-col">

    {{-- ─── DECORATIVE SKY BACKGROUND ─── --}}
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute inset-0"
             style="background: linear-gradient(180deg, #B5D9EC 0%, #DCE9CC 45%, #C9DDB6 70%, #8FBC8A 100%);"></div>

        {{-- Sun --}}
        <div class="absolute"
             style="top: 60px; right: 12%; width: 40px; height: 40px; background: #F1CC8E;
                    box-shadow: 0 -10px 0 4px #F1CC8E, 0 10px 0 4px #F1CC8E,
                                -10px 0 0 4px #F1CC8E, 10px 0 0 4px #F1CC8E,
                                -8px -8px 0 4px #F1CC8E, 8px -8px 0 4px #F1CC8E,
                                -8px 8px 0 4px #F1CC8E, 8px 8px 0 4px #F1CC8E,
                                0 0 40px rgba(241,204,142,0.5);">
        </div>

        {{-- Clouds --}}
        <div class="absolute" style="top: 100px; left: 8%; width: 50px; height: 14px; background: #fff; opacity: 0.85;
             box-shadow: 14px 0 0 5px #fff, 28px 0 0 4px #fff, 4px -7px 0 6px #fff, 20px -7px 0 5px #fff;"></div>
        <div class="absolute" style="top: 160px; left: 70%; width: 40px; height: 12px; background: #fff; opacity: 0.75;
             box-shadow: 12px 0 0 4px #fff, 24px 0 0 3px #fff, 6px -5px 0 4px #fff;"></div>
        <div class="absolute" style="top: 220px; left: 25%; width: 36px; height: 10px; background: #fff; opacity: 0.6;
             box-shadow: 10px 0 0 4px #fff, 20px 0 0 3px #fff;"></div>

        {{-- Hills (bottom) --}}
        <div class="absolute bottom-12 left-0 right-0">
            <div class="absolute" style="bottom: 0; left: 5%;  width: 180px; height: 60px; background: #4E7D4C; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
            <div class="absolute" style="bottom: 0; left: 30%; width: 240px; height: 75px; background: #5C8F58; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
            <div class="absolute" style="bottom: 0; right: 8%; width: 160px; height: 55px; background: #4E7D4C; border-radius: 50% 50% 0 0; opacity: 0.85;"></div>
        </div>

        {{-- Trees --}}
        <div class="absolute" style="bottom: 50px; left: 15%;">
            <div style="width: 8px; height: 22px; background: #5C4632; margin: 0 auto;"></div>
            <div style="width: 36px; height: 26px; background: #4E7D4C; margin-top: -3px;
                        box-shadow: 0 -6px 0 #4E7D4C, 6px 6px 0 -2px #5C8F58;"></div>
        </div>
        <div class="absolute" style="bottom: 45px; right: 18%;">
            <div style="width: 6px; height: 18px; background: #5C4632; margin: 0 auto;"></div>
            <div style="width: 28px; height: 20px; background: #4E7D4C; margin-top: -2px;"></div>
        </div>

        {{-- Ground stripes --}}
        <div class="absolute bottom-0 left-0 right-0" style="height: 48px;
             background: repeating-linear-gradient(90deg, #6B4E32 0, #6B4E32 20px, #5C4632 20px, #5C4632 40px);
             border-top: 3px solid #4E7D4C;"></div>
    </div>

    {{-- ─── MAIN CONTENT ─── --}}
    <div class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6 relative z-10">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-3 mb-6 no-underline group">
            <div class="w-12 h-12 bg-grass-dark border-2 border-soil-dark flex items-center justify-center shadow-cozy-lg
                        group-hover:rotate-6 transition-transform duration-200"
                 style="border-radius: 8px;">
                <x-application-logo class="w-7 h-7 text-corn-light"/>
            </div>
            <div>
                <h1 class="font-pixel text-soil-dark" style="font-size: 14px; text-shadow: 2px 2px 0 rgba(255,255,255,0.5); letter-spacing: 0.05em;">
                    LIFE-SIM
                </h1>
                <p class="font-sans text-soil text-xs mt-0.5">Your Cozy Life Dashboard</p>
            </div>
        </a>

        {{-- Form Card --}}
        <div class="w-full sm:max-w-md bg-white/95 border-2 border-cream-dark shadow-cozy-lg p-6 sm:p-8 backdrop-blur-sm"
             style="border-radius: 8px;">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="font-sans text-soil-dark/60 text-xs mt-6 px-4 py-1 bg-white/60 backdrop-blur-sm"
           style="border-radius: 999px;">
            🌾 Built with Laravel · Livewire · Alpine.js
        </p>
    </div>

    @livewireScriptConfig
</body>
</html>

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

    {{-- ─── LATAR PIXEL: langit berpita + gunung stair-stepped + tanah ─── --}}
    <div class="fixed inset-0 -z-10 pointer-events-none" aria-hidden="true">
        {{-- langit: 3 pita + dither di tiap sambungan --}}
        <div class="absolute inset-x-0" style="top:0; height:34vh; background:#8CB8DE;"></div>
        <div class="absolute inset-x-0" style="top:34vh; height:24vh; background:#A9CBE7;"></div>
        <div class="absolute inset-x-0" style="top:58vh; bottom:0; background:#C7DFF0;"></div>
        <div class="px-dither" style="top:calc(34vh - 4px); --da:#A9CBE7; --db:#8CB8DE;"></div>
        <div class="px-dither" style="top:calc(58vh - 4px); --da:#C7DFF0; --db:#A9CBE7;"></div>

        {{-- gunung stair-stepped di garis horizon --}}
        <div class="px-mtn" style="left:-40px; bottom:122px; width:220px; height:104px; background:#77A874; opacity:.75;"></div>
        <div class="px-mtn" style="left:20%; bottom:122px; width:300px; height:146px; background:#5C8F58; opacity:.8;"></div>
        <div class="px-mtn" style="right:18%; bottom:122px; width:180px; height:88px; background:#4E7D4C; opacity:.85;"></div>
        <div class="px-mtn" style="right:-30px; bottom:122px; width:260px; height:118px; background:#77A874; opacity:.75;"></div>

        {{-- rumput: 2 pita + dither --}}
        <div class="absolute inset-x-0" style="bottom:96px; height:26px; background:#8FBC8A;"></div>
        <div class="absolute inset-x-0" style="bottom:56px; height:40px; background:#6BA368;"></div>
        <div class="px-dither" style="bottom:92px; --da:#6BA368; --db:#8FBC8A;"></div>

        {{-- tanah papan --}}
        <div class="absolute inset-x-0" style="bottom:54px; height:2px; background:#5C4632;"></div>
        <div class="absolute inset-x-0 bottom-0" style="height:54px; background:#83644A;
             background-image:repeating-linear-gradient(90deg, transparent 0 46px, #6E5138 46px 49px);"></div>
    </div>

    {{-- ─── KONTEN UTAMA ─── --}}
    <div class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6 relative z-10">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-3 mb-6 no-underline group">
            <div class="w-12 h-12 bg-grass-dark border-2 border-soil-dark flex items-center justify-center shadow-cozy-lg
                        group-hover:rotate-6 transition-transform duration-200"
                 style="border-radius: 4px;">
                <x-application-logo class="w-7 h-7 text-corn-light"/>
            </div>
            <div>
                <h1 class="font-pixel text-soil-dark" style="font-size: 14px; text-shadow: 2px 2px 0 rgba(255,255,255,0.5); letter-spacing: 0.05em;">
                    LIFE-SIM
                </h1>
                <p class="font-sans text-soil text-xs mt-0.5">Your Cozy Life Dashboard</p>
            </div>
        </a>

        {{-- Kartu form: panel design system yang sama dengan halaman app --}}
        <div class="panel w-full sm:max-w-md p-6 sm:p-8">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="font-sans text-soil-dark/70 text-xs mt-6">
            Built with Laravel · Livewire · Alpine.js
        </p>
    </div>

    @livewireScriptConfig
</body>
</html>

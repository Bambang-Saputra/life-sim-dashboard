<x-app-layout>

    {{-- ══════════════════════════════════════════
         🌾 LIVING FARM BANNER (time-aware, animated)
    ══════════════════════════════════════════ --}}
    <div
        x-data="bannerScene()"
        x-init="init()"
        class="relative w-full overflow-hidden mb-6 border-2 border-soil-dark shadow-cozy-lg"
        style="height: 180px; border-radius: 8px;"
    >
        {{-- ░░░ LAYER 1 — SKY ░░░ --}}
        <div class="absolute inset-0 transition-colors duration-1000" :class="skyClass"></div>

        {{-- ✨ Stars (night only) --}}
        <template x-if="!isDaytime">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute anim-shimmer bg-white" style="top:14px; left:8%;  width:2px; height:2px;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:28px; left:18%; width:2px; height:2px; animation-delay:0.4s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:18px; left:32%; width:3px; height:3px; animation-delay:0.8s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:40px; left:48%; width:2px; height:2px; animation-delay:1.2s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:24px; left:65%; width:2px; height:2px; animation-delay:0.2s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:36px; left:80%; width:3px; height:3px; animation-delay:1.6s;"></div>
                <div class="absolute anim-shimmer bg-white" style="top:50px; left:92%; width:2px; height:2px; animation-delay:0.6s;"></div>
            </div>
        </template>

        {{-- ☁ Drifting Clouds (daytime) --}}
        <template x-if="isDaytime">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute anim-drift" style="top: 18px;">
                    <div style="position:relative; width:32px; height:10px; background:#fff; opacity:0.9;
                                box-shadow: 10px 0 0 4px #fff, 22px 0 0 3px #fff, 4px -5px 0 5px #fff, 16px -5px 0 4px #fff;"></div>
                </div>
                <div class="absolute anim-drift-slow" style="top: 42px;">
                    <div style="position:relative; width:26px; height:9px; background:#fff; opacity:0.7;
                                box-shadow: 8px 0 0 3px #fff, 18px 0 0 3px #fff, 6px -4px 0 4px #fff;"></div>
                </div>
                <div class="absolute anim-drift" style="top: 64px; animation-duration:75s;">
                    <div style="position:relative; width:22px; height:8px; background:#fff; opacity:0.6;
                                box-shadow: 6px 0 0 3px #fff, 14px 0 0 2px #fff;"></div>
                </div>
            </div>
        </template>

        {{-- ☀ Sun --}}
        <template x-if="isDaytime">
            <div class="absolute anim-sun-shine"
                 :style="sunPosition + '; width:28px; height:28px; background:#F1CC8E;
                         box-shadow: 0 -7px 0 3px #F1CC8E, 0 7px 0 3px #F1CC8E,
                                     -7px 0 0 3px #F1CC8E, 7px 0 0 3px #F1CC8E,
                                     -5px -5px 0 3px #F1CC8E, 5px -5px 0 3px #F1CC8E,
                                     -5px 5px 0 3px #F1CC8E, 5px 5px 0 3px #F1CC8E,
                                     0 0 24px rgba(241,204,142,0.6);'"></div>
        </template>
        {{-- 🌙 Moon --}}
        <template x-if="!isDaytime">
            <div class="absolute" style="top:18px; right:14%; width:22px; height:22px; background:#E8DEC4; border-radius:50%;
                 box-shadow: 0 0 18px rgba(232,222,196,0.55), inset -5px 0 0 #B5AFA8;"></div>
        </template>

        {{-- ░░░ LAYER 2 — Distant mountains ░░░ --}}
        <div class="absolute bottom-10 left-0 right-0 pointer-events-none">
            <div class="absolute" style="bottom:0; left:-5%;  width:200px; height:60px; background:#4E7D4C; border-radius:50% 50% 0 0; opacity:0.4;"></div>
            <div class="absolute" style="bottom:0; left:30%;  width:260px; height:80px; background:#3D5A3A; border-radius:50% 50% 0 0; opacity:0.5;"></div>
            <div class="absolute" style="bottom:0; right:-5%; width:180px; height:55px; background:#4E7D4C; border-radius:50% 50% 0 0; opacity:0.4;"></div>
        </div>

        {{-- ░░░ LAYER 3 — Near hills ░░░ --}}
        <div class="absolute bottom-6 left-0 right-0 pointer-events-none">
            <div class="absolute" style="bottom:0; left:8%;   width:130px; height:42px; background:#5C8F58; border-radius:50% 50% 0 0;"></div>
            <div class="absolute" style="bottom:0; left:55%;  width:160px; height:48px; background:#4E7D4C; border-radius:50% 50% 0 0;"></div>
        </div>

        {{-- 🏡 Farmhouse with chimney smoke --}}
        <div class="absolute" style="bottom: 28px; right: 8%;">
            {{-- Smoke particles --}}
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.6; border-radius:50%;"></div>
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.5; border-radius:50%; animation-delay:1s;"></div>
            <div class="absolute anim-smoke" style="bottom: 36px; left: 8px; width:5px; height:5px; background:#fff; opacity:0.4; border-radius:50%; animation-delay:2s;"></div>
            {{-- Chimney --}}
            <div style="position:absolute; bottom:24px; left:6px; width:6px; height:10px; background:#5C4632;"></div>
            {{-- Roof (triangle via borders) --}}
            <div style="width:0; height:0; border-left:18px solid transparent; border-right:18px solid transparent; border-bottom:14px solid #9A3F56; position:absolute; bottom:24px; left:-2px;"></div>
            {{-- Body --}}
            <div style="width:32px; height:24px; background:#D4A373; border:2px solid #5C4632; position:relative;">
                {{-- Door --}}
                <div style="position:absolute; bottom:0; left:11px; width:8px; height:12px; background:#5C4632;"></div>
                {{-- Window --}}
                <div style="position:absolute; top:4px; left:4px; width:6px; height:6px; background:#F1CC8E; border:1px solid #5C4632;"
                     :class="!isDaytime ? 'anim-shimmer' : ''"></div>
            </div>
        </div>

        {{-- 🌳 Tree (large, swaying) --}}
        <div class="absolute" style="bottom: 28px; left: 16%;">
            <div style="width:7px; height:18px; background:#5C4632; margin:0 auto;"></div>
            <div class="anim-sway" style="width:32px; height:24px; background:#4E7D4C; margin-top:-3px;
                        box-shadow: 0 -6px 0 #4E7D4C, 6px 6px 0 -2px #5C8F58, -6px 4px 0 -3px #5C8F58;"></div>
        </div>
        {{-- 🌳 Tree (small) --}}
        <div class="absolute" style="bottom: 24px; left: 35%;">
            <div style="width:5px; height:14px; background:#5C4632; margin:0 auto;"></div>
            <div class="anim-sway" style="width:24px; height:18px; background:#4E7D4C; margin-top:-2px; animation-delay:0.5s;"></div>
        </div>

        {{-- 🌾 Crops swaying (foreground) --}}
        <div class="absolute" style="bottom: 20px; left: 50%; font-size: 14px;">
            <span class="inline-block anim-sway" style="animation-delay: 0s">🌾</span>
            <span class="inline-block anim-sway" style="animation-delay: 0.3s">🌾</span>
            <span class="inline-block anim-sway" style="animation-delay: 0.6s">🌾</span>
        </div>
        <div class="absolute" style="bottom: 20px; left: 28%; font-size: 12px;">
            <span class="inline-block anim-sway" style="animation-delay: 0.4s">🌻</span>
        </div>

        {{-- ✨ Fireflies (night only) --}}
        <template x-if="!isDaytime">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute anim-firefly" style="bottom:50px; left:22%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E;"></div>
                <div class="absolute anim-firefly" style="bottom:70px; left:42%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:1.5s;"></div>
                <div class="absolute anim-firefly" style="bottom:55px; left:58%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:0.8s;"></div>
                <div class="absolute anim-firefly" style="bottom:62px; left:74%; width:3px; height:3px; background:#F1CC8E; border-radius:50%; box-shadow:0 0 6px #F1CC8E; animation-delay:2.2s;"></div>
            </div>
        </template>

        {{-- Sparkles (always) --}}
        <div class="absolute pointer-events-none">
            <div class="absolute anim-shimmer" style="top:90px; left:25%; width:4px; height:4px; background:#FFF8DC; border-radius:50%;
                 box-shadow:0 0 4px #F1CC8E; animation-delay:1s;"></div>
            <div class="absolute anim-shimmer" style="top:110px; left:55%; width:3px; height:3px; background:#FFF8DC; border-radius:50%;
                 box-shadow:0 0 4px #F1CC8E; animation-delay:2.2s;"></div>
        </div>

        {{-- ░░░ LAYER 4 — Fence ░░░ --}}
        <div class="absolute" style="bottom: 18px; left: 0; right: 0; height: 8px;">
            <div style="height: 100%; background: repeating-linear-gradient(
                90deg,
                transparent 0,
                transparent 18px,
                #5C4632 18px,
                #5C4632 22px
            );"></div>
            <div style="position:absolute; top:2px; left:0; right:0; height:3px; background:#5C4632;"></div>
        </div>

        {{-- ░░░ LAYER 5 — Ground stripes (dirt) ░░░ --}}
        <div class="absolute bottom-0 left-0 right-0" style="height: 18px;
             background: repeating-linear-gradient(90deg, #6B4E32 0, #6B4E32 14px, #5C4632 14px, #5C4632 28px);
             border-top: 3px solid #4E7D4C;"></div>

        {{-- ░░░ TITLE OVERLAY ░░░ --}}
        <div class="absolute top-3 left-0 right-0 z-10 px-4 flex items-center justify-between gap-3">
            <div>
                <h1 class="font-pixel"
                    style="font-size: clamp(11px, 2.4vw, 17px); color: #FBF7EC;
                           text-shadow: 2px 2px 0 #5C4632, 3px 3px 0 rgba(0,0,0,0.35); letter-spacing: 0.05em;">
                    LIFE-SIM <span style="color:#F1CC8E">DASHBOARD</span>
                </h1>
                <p class="font-sans text-xs mt-1.5 inline-block px-2.5 py-1 bg-black/25 backdrop-blur-sm"
                   style="color: #FBF7EC; border-radius: 999px; letter-spacing: 0.03em;"
                   x-text="`Selamat ${greeting}, {{ Auth::user()->name }}!`">
                </p>
            </div>

            {{-- Mini flag (decorative) --}}
            <div class="hidden sm:block" style="margin-top: 4px;">
                <div style="width:3px; height:36px; background:#5C4632;"></div>
                <div class="anim-flag" style="position:absolute; left:3px; top:0; width:24px; height:14px; background:#BE546E; border:1px solid #5C4632;"></div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SUMMARY WIDGETS
    ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <div>@livewire('quest-summary')</div>
        <div>@livewire('finance-summary')</div>
    </div>

    <div>@livewire('library-summary')</div>

    <script>
    function bannerScene() {
        return {
            hour: new Date().getHours(),
            skyClass: 'sky-day', isDaytime: true,
            sunPosition: 'top:18px; right:14%', greeting: 'datang',
            init() { this.update(); setInterval(() => this.update(), 60000); },
            update() {
                this.hour = new Date().getHours();
                this.isDaytime = this.hour >= 6 && this.hour < 18;
                if (this.hour >= 5 && this.hour < 10) {
                    this.skyClass = 'sky-morning'; this.sunPosition = 'top:50px; right:18%'; this.greeting = 'pagi';
                } else if (this.hour >= 10 && this.hour < 16) {
                    this.skyClass = 'sky-day'; this.sunPosition = 'top:18px; right:14%'; this.greeting = 'siang';
                } else if (this.hour >= 16 && this.hour < 19) {
                    this.skyClass = 'sky-evening'; this.sunPosition = 'top:60px; right:10%'; this.greeting = 'sore';
                } else {
                    this.skyClass = 'sky-night'; this.greeting = 'malam';
                }
            }
        }
    }
    </script>

</x-app-layout>

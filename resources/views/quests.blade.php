<x-app-layout>
    <x-slot name="title">Quest Board - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div class="page-hero-text">
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Quest Board</h1>
                <p class="page-description">
                    Ruang kerja utama untuk mengelola tugas hidup harian: prioritas, progress, deadline,
                    alarm, catatan dampak, dan history setiap quest.
                </p>
                <div class="page-actions mt-4">
                    <a href="{{ route('finance.index') }}" class="btn-ghost">Open Finance</a>
                    <a href="{{ route('library.index') }}" class="btn-ghost">Open Library</a>
                </div>
            </div>

            {{-- ⚔ Pixel scene: adventurer leveling up --}}
            <div class="page-hero-art" aria-hidden="true">
                {{-- soft glow --}}
                <div class="anim-shimmer" style="position:absolute; top:18px; left:120px; width:90px; height:90px; background:radial-gradient(circle,rgba(241,204,142,0.55),transparent 68%);"></div>

                {{-- LEVEL UP! tag --}}
                <div style="position:absolute; top:6px; left:96px; background:#9A3F56; border:2px solid #5C4632; padding:2px 7px; box-shadow:0 2px 0 #5C4632;">
                    <span class="font-pixel" style="font-size:7px; color:#FBF7EC; letter-spacing:0.08em; white-space:nowrap;">LEVEL UP!</span>
                </div>

                {{-- floating XP stars --}}
                <div class="anim-sway" style="position:absolute; top:40px; left:72px;  color:#E5B567; font-size:14px;">★</div>
                <div class="anim-sway" style="position:absolute; top:30px; left:212px; color:#F1CC8E; font-size:10px; animation-delay:.5s;">★</div>
                <div class="anim-sway" style="position:absolute; top:64px; left:236px; color:#E5B567; font-size:12px; animation-delay:.9s;">✦</div>

                {{-- grass platform --}}
                <div style="position:absolute; bottom:20px; left:78px;  width:150px; height:16px; background:#4E7D4C; border-radius:50% 50% 0 0; opacity:.85;"></div>
                <div style="position:absolute; bottom:20px; left:104px; width:96px;  height:22px; background:#5C8F58; border-radius:50% 50% 0 0;"></div>

                {{-- raised sword (above hero) --}}
                <div style="position:absolute; bottom:96px; left:150px;">
                    <div style="width:5px; height:30px; background:linear-gradient(180deg,#FBF7EC,#C9C4C0); border:1px solid #7B7672; margin:0 auto;"></div>
                    <div style="width:16px; height:4px; background:#C99845; border:1px solid #5C4632; margin:-1px auto 0;"></div>
                    <div style="width:5px; height:5px; background:#E5B567; border:1px solid #5C4632; margin:0 auto; border-radius:50%;"></div>
                </div>

                {{-- hero --}}
                <div style="position:absolute; bottom:30px; left:138px; width:28px; height:40px;">
                    {{-- raised arm --}}
                    <div style="position:absolute; top:6px; left:19px; width:5px; height:13px; background:#E8C7A0; border:1px solid #5C4632; transform:rotate(18deg);"></div>
                    {{-- head + hair --}}
                    <div style="position:absolute; top:2px; left:7px; width:14px; height:13px; background:#E8C7A0; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:0;  left:6px; width:16px; height:6px;  background:#5C4632;"></div>
                    {{-- body --}}
                    <div style="position:absolute; top:14px; left:5px; width:18px; height:16px; background:#4E7D4C; border:1px solid #3D5A3A;"></div>
                    <div style="position:absolute; top:24px; left:5px; width:18px; height:3px;  background:#C99845;"></div>
                    {{-- legs --}}
                    <div style="position:absolute; top:30px; left:7px;  width:6px; height:10px; background:#5C4632;"></div>
                    <div style="position:absolute; top:30px; left:15px; width:6px; height:10px; background:#5C4632;"></div>
                    {{-- shield arm --}}
                    <div style="position:absolute; top:16px; left:0; width:7px; height:10px; background:#77AADD; border:1px solid #4E7AA8; border-radius:2px;"></div>
                </div>

                {{-- XP progress bar --}}
                <div style="position:absolute; bottom:4px; left:84px; width:138px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                        <span class="font-pixel" style="font-size:6px; color:#83644A;">XP</span>
                        <span class="font-pixel" style="font-size:6px; color:#83644A;">LV.7</span>
                    </div>
                    <div style="height:8px; background:#E8DEC4; border:1px solid #C99845; border-radius:4px; overflow:hidden;">
                        <div style="width:68%; height:100%; background:linear-gradient(90deg,#6BA368,#5C8F58);"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $questTotal = \App\Models\Quest::count();
        $questActive = \App\Models\Quest::where('is_completed', false)->count();
        $questImportant = \App\Models\Quest::where('is_important', true)->count();
        $questProgress = $questTotal > 0 ? round(\App\Models\Quest::avg('progress') ?? 0) : 0;
    @endphp

    <section class="metric-strip">
        <div class="metric-tile">
            <p class="metric-label">Total Quests</p>
            <p class="metric-value">{{ $questTotal }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Active</p>
            <p class="metric-value text-grass-dark">{{ $questActive }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Important</p>
            <p class="metric-value text-corn-dark">{{ $questImportant }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Avg Progress</p>
            <p class="metric-value">{{ $questProgress }}%</p>
        </div>
    </section>

    @livewire('quest-board')
</x-app-layout>

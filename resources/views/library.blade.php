<x-app-layout>
    <x-slot name="title">Library Wing - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div class="page-hero-text">
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Library Wing</h1>
                <p class="page-description">
                    Katalog personal untuk film, TV series, anime, dan manga. Browse trending content,
                    simpan ke koleksi, beri rating, dan lihat pola tontonan favoritmu.
                </p>
                <div class="page-actions mt-4">
                    <a href="{{ route('quests.index') }}" class="btn-ghost">Open Quests</a>
                    <a href="{{ route('finance.index') }}" class="btn-ghost">Open Finance</a>
                </div>
            </div>

            {{-- 📚 Pixel scene: cozy hobby nook --}}
            <div class="page-hero-art" aria-hidden="true">
                {{-- lamp glow --}}
                <div class="anim-shimmer" style="position:absolute; top:6px; left:18px; width:90px; height:80px; background:radial-gradient(circle,rgba(241,204,142,0.5),transparent 70%);"></div>

                {{-- floor line --}}
                <div style="position:absolute; bottom:22px; left:8px; right:8px; height:4px; background:#C9A86A; opacity:.4; border-radius:2px;"></div>

                {{-- hanging lamp (top-left) --}}
                <div style="position:absolute; top:0; left:50px; width:2px; height:14px; background:#5C4632; margin:0 auto;"></div>
                <div style="position:absolute; top:13px; left:42px; width:20px; height:11px; background:#E5B567; border:2px solid #5C4632; border-radius:0 0 10px 10px;"></div>

                {{-- BOOKSHELF (left) --}}
                <div style="position:absolute; bottom:26px; left:14px; width:96px; height:84px; background:#8B5A2B; border:2px solid #5C4632; box-shadow:inset 0 0 0 2px #A06A35;">
                    {{-- shelf divider --}}
                    <div style="position:absolute; top:40px; left:0; right:0; height:3px; background:#5C4632;"></div>
                    {{-- top row books --}}
                    <div style="position:absolute; top:6px; left:7px;  width:8px;  height:30px; background:#BE546E; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:9px; left:17px; width:8px;  height:27px; background:#6BA368; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:5px; left:27px; width:8px;  height:31px; background:#77AADD; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:11px;left:37px; width:8px;  height:25px; background:#E5B567; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:14px;left:48px; width:24px; height:22px; background:#9A3F56; border:1px solid #5C4632; transform:rotate(8deg); transform-origin:bottom left;"></div>
                    <div style="position:absolute; top:7px; left:74px; width:8px;  height:29px; background:#5C8F58; border:1px solid #5C4632;"></div>
                    {{-- bottom row books --}}
                    <div style="position:absolute; top:48px; left:7px;  width:8px; height:30px; background:#E5B567; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:51px; left:17px; width:8px; height:27px; background:#BE546E; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:47px; left:27px; width:8px; height:31px; background:#83644A; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:50px; left:37px; width:8px; height:28px; background:#77AADD; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:49px; left:47px; width:8px; height:29px; background:#6BA368; border:1px solid #5C4632;"></div>
                    {{-- little plant on shelf --}}
                    <div style="position:absolute; top:62px; left:74px; width:12px; height:8px; background:#C97B4A; border:1px solid #5C4632;"></div>
                    <div style="position:absolute; top:53px; left:76px; width:8px; height:11px; background:#4E7D4C; border-radius:50% 50% 0 0;"></div>
                </div>

                {{-- TV / monitor (right) — movies & anime --}}
                <div style="position:absolute; bottom:30px; left:158px; width:92px; height:62px;">
                    {{-- screen --}}
                    <div style="position:absolute; top:0; width:92px; height:54px; background:linear-gradient(160deg,#3D5A6B,#27414F); border:3px solid #5C4632; border-radius:5px;">
                        {{-- play button --}}
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:0;height:0;border-top:9px solid transparent;border-bottom:9px solid transparent;border-left:15px solid #FBF7EC; opacity:.92;"></div>
                        {{-- scanline shimmer --}}
                        <div class="anim-shimmer" style="position:absolute; top:6px; left:8px; width:30px; height:3px; background:rgba(255,255,255,.35);"></div>
                    </div>
                    {{-- stand --}}
                    <div style="position:absolute; bottom:0; left:40px; width:10px; height:9px; background:#5C4632;"></div>
                    <div style="position:absolute; bottom:0; left:30px; width:32px; height:4px; background:#5C4632; border-radius:2px;"></div>
                </div>

                {{-- steaming mug --}}
                <div style="position:absolute; bottom:26px; left:128px;">
                    <div class="anim-sway" style="position:absolute; bottom:18px; left:5px; width:3px; height:8px; background:#fff; opacity:.5; border-radius:2px;"></div>
                    <div style="width:16px; height:14px; background:#BE546E; border:2px solid #5C4632; border-radius:0 0 5px 5px;"></div>
                    <div style="position:absolute; top:2px; right:-5px; width:7px; height:8px; border:2px solid #5C4632; border-radius:0 6px 6px 0;"></div>
                </div>

                {{-- potted plant (right corner) --}}
                <div style="position:absolute; bottom:26px; left:262px;">
                    <div style="position:absolute; bottom:0; width:20px; height:14px; background:#C97B4A; border:2px solid #5C4632; border-radius:0 0 4px 4px;"></div>
                    <div class="anim-sway" style="position:absolute; bottom:12px; left:1px; width:8px; height:16px; background:#4E7D4C; border-radius:50% 50% 0 50%;"></div>
                    <div class="anim-sway" style="position:absolute; bottom:12px; left:9px; width:9px; height:20px; background:#5C8F58; border-radius:50% 50% 50% 0; animation-delay:.4s;"></div>
                </div>
            </div>
        </div>
    </section>

    @php
        $libraryTotal = \App\Models\LibraryItem::count();
        $ratedTotal = \App\Models\LibraryItem::whereNotNull('personal_rating')->count();
        $avgRating = \App\Models\LibraryItem::whereNotNull('personal_rating')->avg('personal_rating');
        $completedTotal = \App\Models\LibraryItem::where('status', 'completed')->count();
    @endphp

    <section class="metric-strip">
        <div class="metric-tile">
            <p class="metric-label">Collection</p>
            <p class="metric-value">{{ $libraryTotal }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Rated</p>
            <p class="metric-value text-corn-dark">{{ $ratedTotal }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Avg Rating</p>
            <p class="metric-value">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</p>
        </div>
        <div class="metric-tile">
            <p class="metric-label">Completed</p>
            <p class="metric-value text-grass-dark">{{ $completedTotal }}</p>
        </div>
    </section>

    @if(empty(config('services.tmdb.api_key')) || config('services.tmdb.api_key') === 'your_tmdb_api_key_here')
        <div class="mb-5 panel p-4 border-l-4 !border-l-corn">
            <p class="font-sans font-semibold text-soil-dark text-sm mb-1">TMDB API key belum aktif</p>
            <p class="font-sans text-soil text-sm">
                Movie dan TV search membutuhkan key gratis dari
                <a href="https://www.themoviedb.org/settings/api" target="_blank" class="text-grass-dark font-semibold underline">themoviedb.org/settings/api</a>.
                Anime dan manga tetap berjalan memakai Jikan API tanpa key.
            </p>
        </div>
    @endif

    @livewire('library-wing')
</x-app-layout>

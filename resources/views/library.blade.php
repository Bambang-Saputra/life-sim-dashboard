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
            <div class="page-hero-art is-live" aria-hidden="false">
                <div class="pxs"
                     x-data="pixelScene"
                     :class="burst && 'is-burst'"
                     @mousemove="onMove($event)"
                     @mouseleave="resetPointer"
                     :style="`--px:${px};--py:${py}`">
                    {{-- dinding hangat + dither --}}
                    <div class="absolute inset-x-0" style="top:0; height:54px; background:#F2E2C8;"></div>
                    <div class="absolute inset-x-0" style="top:54px; height:56px; background:#E8D4B4;"></div>
                    <div class="px-dither" style="top:50px; --da:#E8D4B4; --db:#F2E2C8;"></div>
                    {{-- lantai papan --}}
                    <div class="absolute inset-x-0" style="top:110px; height:2px; background:#5C4632;"></div>
                    <div class="absolute inset-x-0" style="top:112px; height:44px; background:#B8804A; background-image:repeating-linear-gradient(90deg, transparent 0 46px, #9A6A3D 46px 49px);"></div>

                    <div class="par par-far">
                        {{-- jendela senja + matahari --}}
                        <div class="absolute" style="left:104px; top:8px; width:88px; height:62px; background:#5C4632;">
                            <span class="absolute" style="left:6px; right:6px; top:6px; height:18px; background:#EDBA92;"></span>
                            <span class="absolute" style="left:6px; right:6px; top:24px; height:18px; background:#D98F72;"></span>
                            <span class="absolute" style="left:6px; right:6px; top:42px; bottom:6px; background:#B97862;"></span>
                            <span class="absolute anim-shimmer" style="left:50%; top:24px; width:14px; height:14px; margin-left:-7px; background:#F1CC8E; box-shadow:0 0 12px rgba(241,204,142,0.85);"></span>
                            <span class="absolute" style="left:50%; top:6px; bottom:6px; width:4px; margin-left:-2px; background:#5C4632;"></span>
                            <span class="absolute" style="left:6px; right:6px; top:50%; height:4px; margin-top:-2px; background:#5C4632;"></span>
                        </div>
                        {{-- bintang rating melayang --}}
                        <div class="px-spark" style="left:76px; top:16px;"></div>
                        <div class="px-spark" style="left:92px; top:34px; animation-delay:.7s;"></div>
                    </div>

                    <div class="par par-mid">
                        {{-- TV interaktif: klik = layar menyala --}}
                        <button type="button" @click="pop"
                                style="left:12px; top:52px; width:74px; height:66px;"
                                title="Klik untuk menyalakan TV"
                                aria-label="TV, klik untuk menyalakan layar">
                            <span class="lx-screen"><span class="lx-play"></span></span>
                            <span class="absolute" style="left:50%; bottom:12px; width:16px; height:8px; margin-left:-8px; background:#5C4632;"></span>
                            <span class="absolute" style="left:50%; bottom:4px; width:40px; height:8px; margin-left:-20px; background:#83644A; box-shadow: inset 0 -3px 0 #5C4632;"></span>
                        </button>
                        {{-- rak buku: tiap buku hover naik --}}
                        <div class="absolute" style="right:12px; top:34px; width:84px; height:88px; background:#5C4632;">
                            <span class="absolute" style="inset:5px; background:#8A6236;"></span>
                            <span class="absolute" style="left:5px; right:5px; top:44px; height:5px; background:#5C4632;"></span>
                            <span class="absolute" style="left:9px; top:12px; height:32px; width:66px;">
                                <span class="lx-book" style="left:0;   height:26px; --bc:#6BA368;"></span>
                                <span class="lx-book" style="left:12px; height:30px; --bc:#77AADD;"></span>
                                <span class="lx-book" style="left:24px; height:24px; --bc:#BE546E;"></span>
                                <span class="lx-book" style="left:36px; height:30px; --bc:#E5B567;"></span>
                                <span class="lx-book" style="left:48px; height:27px; --bc:#9A3F56;"></span>
                            </span>
                            <span class="absolute" style="left:9px; top:52px; height:30px; width:66px;">
                                <span class="lx-book" style="left:0;   height:28px; --bc:#C99845;"></span>
                                <span class="lx-book" style="left:12px; height:24px; --bc:#4E7D4C;"></span>
                                <span class="lx-book" style="left:24px; height:29px; --bc:#5588BB;"></span>
                                <span class="lx-book" style="left:36px; height:25px; --bc:#D4869A;"></span>
                                <span class="lx-book" style="left:48px; height:28px; --bc:#8FBC8A;"></span>
                            </span>
                        </div>
                    </div>

                    <div class="par par-near">
                        {{-- karpet --}}
                        <div class="absolute" style="left:96px; top:132px; width:104px; height:14px; background:#BE546E; box-shadow: inset 0 3px 0 #D4869A; clip-path: polygon(8% 0, 92% 0, 100% 50%, 92% 100%, 8% 100%, 0 50%);"></div>
                        {{-- kursi baca --}}
                        <div class="absolute" style="left:116px; top:84px; width:56px; height:44px;">
                            <span class="absolute" style="left:0; top:0; width:14px; height:38px; background:#BE546E; box-shadow: inset 0 4px 0 #D4869A;"></span>
                            <span class="absolute" style="left:12px; top:20px; width:36px; height:18px; background:#BE546E; box-shadow: inset 0 4px 0 #D4869A;"></span>
                            <span class="absolute" style="right:0; top:12px; width:10px; height:26px; background:#9A3F56;"></span>
                            <span class="absolute" style="left:0; bottom:0; width:56px; height:6px; background:#9A3F56;"></span>
                        </div>
                        {{-- tanaman --}}
                        <div class="absolute" style="left:88px; top:88px; width:20px; height:36px;">
                            <span class="absolute anim-sway" style="left:4px; top:0; width:12px; height:18px; background:#4E7D4C; clip-path: polygon(50% 0, 100% 55%, 78% 100%, 22% 100%, 0 55%);"></span>
                            <span class="absolute" style="left:2px; bottom:0; width:16px; height:12px; background:#B97862; clip-path: polygon(6% 0, 94% 0, 80% 100%, 20% 100%);"></span>
                        </div>
                    </div>
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

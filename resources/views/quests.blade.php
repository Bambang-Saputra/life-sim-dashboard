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
            @php
                $earnedXp = (int) \App\Models\Quest::where('is_completed', true)->sum('xp_reward');
                $heroLevel = intdiv($earnedXp, 100) + 1;
                $heroLevelProgress = $earnedXp % 100;
            @endphp
            <div class="page-hero-art is-live" aria-hidden="false">
                <div class="pxs"
                     x-data="pixelScene"
                     :class="burst && 'is-burst'"
                     @mousemove="onMove($event)"
                     @mouseleave="resetPointer"
                     :style="`--px:${px};--py:${py}`">
                    {{-- langit senja: 4 pita + dither --}}
                    <div class="absolute inset-x-0" style="top:0; height:40px; background:#574B6B;"></div>
                    <div class="absolute inset-x-0" style="top:40px; height:36px; background:#8A6478;"></div>
                    <div class="absolute inset-x-0" style="top:76px; height:30px; background:#C08A6E;"></div>
                    <div class="absolute inset-x-0" style="top:106px; height:22px; background:#EBBE94;"></div>
                    <div class="px-dither" style="top:36px; --da:#8A6478; --db:#574B6B;"></div>
                    <div class="px-dither" style="top:72px; --da:#C08A6E; --db:#8A6478;"></div>
                    <div class="px-dither" style="top:102px; --da:#EBBE94; --db:#C08A6E;"></div>

                    <div class="par par-far">
                        {{-- matahari terbenam stepped --}}
                        <div class="px-halfsun" style="left:118px; top:104px; width:64px; height:24px; background:#F1CC8E; filter:drop-shadow(0 0 14px rgba(241,204,142,0.55));"></div>
                        {{-- gunung stair-stepped --}}
                        <div class="px-mtn" style="left:-24px; top:64px; width:120px; height:64px; background:#4C4160;"></div>
                        <div class="px-mtn" style="right:-20px; top:52px; width:140px; height:76px; background:#4C4160;"></div>
                    </div>

                    {{-- tanah --}}
                    <div class="absolute inset-x-0" style="top:128px; height:4px; background:#8FBC8A;"></div>
                    <div class="absolute inset-x-0" style="top:132px; height:12px; background:#6BA368;"></div>
                    <div class="absolute inset-x-0" style="top:144px; height:2px; background:#5C4632;"></div>
                    <div class="absolute inset-x-0" style="top:146px; height:10px; background:#83644A;"></div>

                    <div class="par par-mid">
                        {{-- bendera --}}
                        <div class="absolute" style="right:34px; top:58px; width:4px; height:74px; background:#5C4632;">
                            <span class="qx-flagcloth"></span>
                        </div>
                        {{-- sparkles --}}
                        <div class="px-spark" style="left:56px; top:44px;"></div>
                        <div class="px-spark" style="left:230px; top:30px; animation-delay:.6s;"></div>
                        <div class="px-spark" style="left:96px; top:20px; animation-delay:1.1s;"></div>
                    </div>

                    <div class="par par-near">
                        {{-- trofi interaktif: klik = burst sparkle --}}
                        <button type="button" @click="pop"
                                style="left:50%; top:26px; transform:translateX(-50%); width:90px; height:104px;"
                                title="Level {{ $heroLevel }} · {{ number_format($earnedXp) }} XP"
                                aria-label="Trofi level {{ $heroLevel }}, klik untuk merayakan">
                            <span class="qx-plate">LV.{{ $heroLevel }}</span>
                            <span class="qx-trophy-cup"></span>
                            <span class="qx-star"></span>
                            <span class="qx-taper"></span>
                            <span class="qx-stem"></span>
                            <span class="qx-base"></span>
                        </button>
                        <div class="px-burst" style="left:50%; top:80px;"><i></i><i></i><i></i><i></i><i></i></div>
                        {{-- progress XP menuju level berikutnya --}}
                        <div class="absolute" style="left:50%; top:136px; transform:translateX(-50%); width:110px; height:8px; background:#5C4632; border:2px solid #3E2F22; overflow:hidden;">
                            <div style="width: {{ $heroLevelProgress }}%; height:100%; background:#E5B567; box-shadow: inset 0 2px 0 #F1CC8E;"></div>
                        </div>
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

    <div class="grid grid-cols-1 xl:grid-cols-[2fr,1fr] gap-5 mb-5">
        <div>@livewire('quest-board')</div>
        <div class="space-y-5">
            @livewire('habit-manager')

            {{-- ── RAK ACHIEVEMENT ── --}}
            @php $achievements = \App\Support\Achievements::all(); @endphp
            <div class="panel">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="section-title">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-corn-dark">
                            <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/>
                        </svg>
                        ACHIEVEMENTS
                    </h2>
                    <span class="font-sans text-soil text-xs">
                        {{ collect($achievements)->where('unlocked', true)->count() }}/{{ count($achievements) }}
                    </span>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($achievements as $a)
                        <div class="ach-tile {{ $a['unlocked'] ? 'is-unlocked' : '' }}"
                             title="{{ $a['title'] }}: {{ $a['desc'] }}{{ $a['unlocked'] ? '' : ' (terkunci)' }}">
                            <span class="ach-icon">{{ $a['unlocked'] ? $a['icon'] : '❓' }}</span>
                            <span class="ach-label">{{ $a['unlocked'] ? $a['title'] : '???' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

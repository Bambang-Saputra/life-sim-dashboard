<div
    x-data="{
        toast: false, toastMsg: '', toastVariant: 'success',
        showToast(msg, variant = 'success') {
            this.toastMsg = msg; this.toastVariant = variant; this.toast = true;
            setTimeout(() => this.toast = false, 3000);
        }
    }"
    @library-item-added.window="showToast($event.detail.title, 'success')"
    @search-failed.window="showToast($event.detail.message || 'Pencarian gagal', 'error')"
    class="panel"
>

    {{-- ── HEADER ── --}}
    <div class="flex items-center justify-between mb-5">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11M9 4v6M15 4v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            LIBRARY WING
        </h2>
        <span class="font-sans text-soil text-sm">
            <span class="font-semibold text-soil-dark">{{ $this->library->count() }}</span>
            <span class="text-stone">titles</span>
        </span>
    </div>

    {{-- ── TABS ── --}}
    <div class="flex gap-1.5 mb-5 flex-wrap">
        @foreach([
            'search'     => ['🔍', 'Search'],
            'collection' => ['📚', 'My Collection'],
            'stats'      => ['📊', 'Stats'],
        ] as $tab => $cfg)
            <button
                wire:click="$set('activeTab', '{{ $tab }}')"
                class="filter-btn {{ $activeTab === $tab ? 'is-active' : '' }} px-4 py-2"
            >{{ $cfg[0] }} {{ $cfg[1] }}</button>
        @endforeach
    </div>

    {{-- ══════════════════════════
         TAB: SEARCH
    ══════════════════════════ --}}
    @if($activeTab === 'search')
        <form wire:submit.prevent="submitSearch" class="form-shell">
            <div class="form-shell-header">
                <div>
                    <p class="form-shell-title">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Discover Media
                    </p>
                    <p class="form-shell-subtitle">Browse trending content atau cari judul spesifik dari TMDB dan Jikan.</p>
                </div>
                <span class="tag-sky">{{ ucfirst($searchType) }}</span>
            </div>
            <div class="form-shell-body flex flex-col lg:flex-row gap-2">
            <input
                wire:model="searchQuery"
                type="text"
                placeholder="Ketik judul lalu tekan Enter atau klik Search..."
                class="input-pixel flex-1"
                minlength="2"
                required
            >
            <select wire:model.live="searchType" class="input-pixel flex-shrink-0" style="width: 140px;">
                <option value="movie">🎬 Movie</option>
                <option value="tv">📺 TV Series</option>
                <option value="anime">⛩ Anime</option>
                <option value="manga">📖 Manga</option>
            </select>
            <button type="submit" wire:loading.attr="disabled" wire:target="search" class="btn-primary px-4 flex-shrink-0">
                <span wire:loading.remove wire:target="search" class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                        <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Search
                </span>
                <span wire:loading wire:target="search" class="animate-blink">...</span>
            </button>
            </div>
        </form>

        @error('searchQuery')
            <p class="font-sans text-berry text-xs mb-3">⚠ {{ $message }}</p>
        @enderror

        @php
            $typeLabel = match($searchType) {
                'movie' => '🎬 Movies',
                'tv'    => '📺 TV Series',
                'anime' => '⛩ Anime',
                'manga' => '📖 Manga',
                default => 'Items',
            };
            $sectionHeading = !empty($searchQuery) && count($searchResults)
                ? "📋 Hasil pencarian: \"{$searchQuery}\""
                : "🔥 Trending {$typeLabel} minggu ini";
        @endphp

        @if($isSearching)
            <div class="empty-state">
                <p class="font-pixel text-soil animate-blink" style="font-size: 9px;">SEARCHING...</p>
            </div>
        @elseif(!empty($searchQuery) && count($searchResults) === 0)
            {{-- Kosong hasil search --}}
            <div class="empty-state">
                <p class="font-pixel text-soil" style="font-size: 9px;">NO RESULTS FOUND</p>
                <p class="font-sans text-stone text-sm mt-2">Coba kata kunci lain atau ganti tipe pencarian</p>
            </div>
        @else
            {{-- Default trending OR hasil search --}}
            @php $items = count($searchResults) ? $searchResults : $defaultItems; @endphp

            <div class="flex items-center justify-between mb-3">
                <p class="font-sans font-semibold text-soil-dark text-sm">{{ $sectionHeading }}</p>
                @if(empty($searchQuery) && count($defaultItems))
                    <span class="font-sans text-stone text-xs">{{ count($defaultItems) }} populer</span>
                @endif
            </div>

            @if($isLoadingDefaults || $isSearching)
                <div class="empty-state">
                    <p class="font-pixel text-soil animate-blink" style="font-size: 9px;">
                        {{ $isSearching ? 'SEARCHING...' : 'LOADING...' }}
                    </p>
                    <p class="font-sans text-stone text-sm mt-2">Mengambil dari API...</p>
                </div>
            @elseif(empty($items))
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" class="w-10 h-10 text-cream-dark mx-auto mb-3">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                        <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <p class="font-sans text-stone text-sm">Tidak bisa memuat daftar. Cek koneksi atau coba search manual.</p>
                </div>
            @else
                <div
                    id="library-grid"
                    @library-page-changed.window="document.getElementById('library-grid')?.scrollIntoView({behavior:'smooth', block:'start'})"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"
                >
                    @foreach($items as $item)
                        <div class="card-item p-2.5 flex flex-col gap-2 group">
                            <div class="relative">
                                @if(!empty($item['cover_image']))
                                    <img src="{{ $item['cover_image'] }}" alt="{{ $item['title'] }}"
                                        class="w-full aspect-[2/3] object-cover" loading="lazy" style="border-radius: 4px;">
                                @else
                                    <div class="w-full aspect-[2/3] flex items-center justify-center bg-cream border border-cream-dark"
                                         style="border-radius: 4px;">
                                        <span class="font-sans text-stone text-xs">No Image</span>
                                    </div>
                                @endif

                                {{-- Rating badge --}}
                                @if(!empty($item['tmdb_rating']) && $item['tmdb_rating'] > 0)
                                    <span class="absolute top-1 right-1 bg-soil-dark/85 text-corn-light font-sans font-semibold text-xs px-1.5 py-0.5 flex items-center gap-0.5"
                                          style="border-radius: 4px;">
                                        ★ {{ number_format($item['tmdb_rating'], 1) }}
                                    </span>
                                @elseif(!empty($item['mal_rating']) && $item['mal_rating'] > 0)
                                    <span class="absolute top-1 right-1 bg-soil-dark/85 text-corn-light font-sans font-semibold text-xs px-1.5 py-0.5 flex items-center gap-0.5"
                                          style="border-radius: 4px;">
                                        ★ {{ number_format($item['mal_rating'], 1) }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-h-0">
                                <p class="font-sans font-semibold text-soil-dark text-xs leading-tight line-clamp-2">
                                    {{ $item['title'] }}
                                </p>
                                @if(!empty($item['release_year']))
                                    <p class="font-sans text-stone text-xs mt-0.5">{{ $item['release_year'] }}</p>
                                @endif
                            </div>

                            <button wire:click="addToLibrary({{ json_encode($item) }})"
                                wire:loading.attr="disabled"
                                wire:target="addToLibrary"
                                class="btn-primary w-full mt-auto py-1.5 text-xs">
                                + Add to Library
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- ═══════ PAGINATION ═══════ --}}
                @if($totalPages > 1)
                    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 p-3 bg-cream/50 border border-cream-dark"
                         style="border-radius: 6px;">

                        {{-- Info --}}
                        <div class="font-sans text-soil text-xs flex items-center gap-2">
                            <span>Halaman <span class="font-semibold text-soil-dark">{{ $currentPage }}</span> dari <span class="font-semibold text-soil-dark">{{ $totalPages }}</span></span>
                            @if($totalResults > 0)
                                <span class="text-stone">·</span>
                                <span>{{ number_format($totalResults, 0, ',', '.') }} item total</span>
                            @endif
                        </div>

                        {{-- Page buttons --}}
                        <div class="flex items-center gap-1 flex-wrap">
                            {{-- First --}}
                            <button type="button" wire:click="goToPage(1)"
                                    wire:loading.attr="disabled"
                                    @disabled($currentPage <= 1)
                                    class="btn-icon disabled:opacity-30 disabled:cursor-not-allowed"
                                    title="First page">
                                <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                    <path d="M18 18l-6-6 6-6M12 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>

                            {{-- Prev --}}
                            <button type="button" wire:click="prevPage"
                                    wire:loading.attr="disabled"
                                    @disabled($currentPage <= 1)
                                    class="btn-ghost py-1.5 px-3 disabled:opacity-30 disabled:cursor-not-allowed"
                                    style="font-size: 11px;">
                                <svg viewBox="0 0 24 24" fill="none" class="w-3 h-3"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                Prev
                            </button>

                            {{-- Page numbers (smart range) --}}
                            @php
                                $start = max(1, $currentPage - 2);
                                $end   = min($totalPages, $currentPage + 2);
                                if ($end - $start < 4) {
                                    if ($start === 1) $end = min($totalPages, $start + 4);
                                    if ($end === $totalPages) $start = max(1, $end - 4);
                                }
                            @endphp

                            @if($start > 1)
                                <span class="font-sans text-stone text-xs px-1">…</span>
                            @endif

                            @for($p = $start; $p <= $end; $p++)
                                <button type="button" wire:click="goToPage({{ $p }})"
                                        wire:loading.attr="disabled"
                                        class="font-sans font-semibold text-xs w-8 h-8 border transition-colors duration-100
                                            {{ $p === $currentPage
                                                ? 'bg-grass-dark text-white border-grass-dark'
                                                : 'bg-white text-soil-dark border-cream-dark hover:bg-cream' }}"
                                        style="border-radius: 4px;">{{ $p }}</button>
                            @endfor

                            @if($end < $totalPages)
                                <span class="font-sans text-stone text-xs px-1">…</span>
                            @endif

                            {{-- Next --}}
                            <button type="button" wire:click="nextPage"
                                    wire:loading.attr="disabled"
                                    @disabled($currentPage >= $totalPages)
                                    class="btn-ghost py-1.5 px-3 disabled:opacity-30 disabled:cursor-not-allowed"
                                    style="font-size: 11px;">
                                Next
                                <svg viewBox="0 0 24 24" fill="none" class="w-3 h-3"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </button>

                            {{-- Last --}}
                            <button type="button" wire:click="goToPage({{ $totalPages }})"
                                    wire:loading.attr="disabled"
                                    @disabled($currentPage >= $totalPages)
                                    class="btn-icon disabled:opacity-30 disabled:cursor-not-allowed"
                                    title="Last page">
                                <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                    <path d="M6 18l6-6-6-6M12 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Subtle loading indicator on page change --}}
                    <div wire:loading wire:target="goToPage,nextPage,prevPage,submitSearch"
                         class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-soil-dark text-cream-light font-sans text-sm px-4 py-2 border border-corn shadow-cozy-lg"
                         style="border-radius: 999px;">
                        <span class="animate-blink">⏳ Loading page {{ $currentPage }}...</span>
                    </div>
                @endif
            @endif
        @endif
    @endif

    {{-- ══════════════════════════
         TAB: MY COLLECTION
    ══════════════════════════ --}}
    @if($activeTab === 'collection')
        {{-- Toolbar: Filters + View Toggle + Sort --}}
        <div class="flex flex-wrap gap-2 mb-4 items-center">
            <div class="flex gap-1 flex-wrap">
                @foreach(['all' => 'All', 'movie' => '🎬', 'tv' => '📺', 'anime' => '⛩', 'manga' => '📖'] as $val => $label)
                    <button wire:click="$set('filterType', '{{ $val }}')"
                        class="filter-btn px-2.5 {{ $filterType === $val ? 'is-active' : '' }}">{{ $label }}</button>
                @endforeach
            </div>

            <div class="w-px bg-cream-dark self-stretch hidden sm:block"></div>

            <div class="flex gap-1 flex-wrap">
                @foreach(['all' => 'All', 'plan_to' => 'Plan', 'ongoing' => 'Ongoing', 'completed' => 'Done', 'dropped' => 'Drop'] as $val => $label)
                    <button wire:click="$set('filterStatus', '{{ $val }}')"
                        class="filter-btn px-2.5 {{ $filterStatus === $val ? 'is-active' : '' }}">{{ $label }}</button>
                @endforeach
            </div>

            <div class="ml-auto flex gap-2 items-center">
                {{-- Sort dropdown --}}
                <select wire:model.live="collectionSort" class="input-pixel py-1.5 px-2.5" style="width: auto; font-size: 11px;">
                    <option value="rating_desc">⭐ Rating: Tertinggi</option>
                    <option value="rating_asc">⭐ Rating: Terendah</option>
                    <option value="title">A→Z</option>
                    <option value="latest">Terbaru</option>
                </select>

                {{-- View toggle --}}
                <div class="flex border border-cream-dark bg-white" style="border-radius: 4px;">
                    <button wire:click="$set('collectionView', 'grid')"
                        class="font-sans text-xs px-2.5 py-1.5 transition-colors duration-100
                            {{ $collectionView === 'grid' ? 'bg-grass-dark text-white' : 'text-soil-dark hover:bg-cream' }}"
                        title="Grid view">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5 inline">
                            <rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                            <rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                            <rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                            <rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                    <button wire:click="$set('collectionView', 'tier')"
                        class="font-sans text-xs px-2.5 py-1.5 transition-colors duration-100
                            {{ $collectionView === 'tier' ? 'bg-grass-dark text-white' : 'text-soil-dark hover:bg-cream' }}"
                        title="Tier list view">
                        <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5 inline">
                            <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tier
                    </button>
                </div>
            </div>
        </div>

        @if($this->library->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" class="w-10 h-10 text-cream-dark mx-auto mb-3">
                    <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="font-pixel text-soil" style="font-size: 9px;">COLLECTION EMPTY</p>
                <p class="font-sans text-stone text-sm mt-2">Go to <span class="font-semibold text-grass-dark">Search</span> to add your first title</p>
            </div>
        @elseif($collectionView === 'tier')
            {{-- ═══════ TIER LIST VIEW ═══════ --}}
            @php
                $tierColors = [
                    'corn'  => ['bg' => 'bg-corn-light/55',  'border' => 'border-corn',     'label' => 'bg-corn-dark text-cream-light'],
                    'grass' => ['bg' => 'bg-grass-light/45', 'border' => 'border-grass',    'label' => 'bg-grass-dark text-white'],
                    'sky'   => ['bg' => 'bg-sky-light/45',   'border' => 'border-sky',      'label' => 'bg-sky-dark text-white'],
                    'soil'  => ['bg' => 'bg-cream',          'border' => 'border-soil',     'label' => 'bg-soil text-cream-light'],
                    'berry' => ['bg' => 'bg-berry-light/40', 'border' => 'border-berry',    'label' => 'bg-berry-dark text-cream-light'],
                    'stone' => ['bg' => 'bg-stone-light/55', 'border' => 'border-stone',    'label' => 'bg-stone-dark text-cream-light'],
                ];
            @endphp

            <div class="space-y-2.5">
                @foreach($this->libraryByTier as $key => $tier)
                    @if($tier['items']->isEmpty()) @continue @endif
                    @php $c = $tierColors[$tier['color']]; @endphp

                    <div class="border-2 {{ $c['border'] }} {{ $c['bg'] }} overflow-hidden" style="border-radius: 6px;">
                        <div class="flex">
                            {{-- Tier label (kiri, vertikal) --}}
                            <div class="{{ $c['label'] }} flex flex-col items-center justify-center font-pixel py-3 flex-shrink-0"
                                 style="width: 78px; font-size: 18px;">
                                <span class="leading-none">{{ $key }}</span>
                                <span class="text-[8px] mt-1 px-2 text-center leading-tight opacity-90">
                                    {{ Str::after($tier['label'], '· ') }}
                                </span>
                            </div>

                            {{-- Tier items (horizontal scroll) --}}
                            <div class="flex-1 p-3 overflow-x-auto">
                                <div class="flex gap-2.5">
                                    @foreach($tier['items'] as $item)
                                        <div class="flex-shrink-0 group relative" style="width: 88px;">
                                            @if($item->cover_image)
                                                <img src="{{ $item->cover_image }}" alt="{{ $item->title }}"
                                                    class="w-full aspect-[2/3] object-cover border border-cream-dark"
                                                    style="border-radius: 4px;" loading="lazy">
                                            @else
                                                <div class="w-full aspect-[2/3] flex items-center justify-center bg-cream border border-cream-dark"
                                                     style="border-radius: 4px;">
                                                    <span class="font-sans text-stone text-xs">No Img</span>
                                                </div>
                                            @endif

                                            @if($item->personal_rating)
                                                <span class="absolute top-1 right-1 bg-soil-dark/85 text-corn-light font-sans font-bold text-xs px-1.5 py-0.5"
                                                      style="border-radius: 4px; font-size: 10px;">
                                                    ★ {{ $item->personal_rating }}
                                                </span>
                                            @endif

                                            <p class="font-sans font-medium text-soil-dark text-xs mt-1 leading-tight line-clamp-2">
                                                {{ Str::limit($item->title, 30) }}
                                            </p>

                                            {{-- Hover actions overlay --}}
                                            <div class="opacity-0 group-hover:opacity-100 absolute inset-x-0 bottom-12 flex gap-1 justify-center p-1 transition-opacity duration-150">
                                                <button wire:click="startEditRating({{ $item->id }})"
                                                    class="bg-soil-dark text-corn-light text-xs px-2 py-1 hover:bg-soil"
                                                    style="border-radius: 4px;" title="Rate">★</button>
                                                <button wire:click="removeFromLibrary({{ $item->id }})" wire:confirm="Remove?"
                                                    class="bg-berry-dark text-white text-xs px-2 py-1 hover:bg-berry"
                                                    style="border-radius: 4px;" title="Remove">✕</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Legend --}}
            <p class="font-sans text-stone text-xs mt-4 text-center">
                Tier dihitung otomatis dari rating personalmu: <strong>S</strong> 9-10 · <strong>A</strong> 7.5-8.9 · <strong>B</strong> 6-7.4 · <strong>C</strong> 4-5.9 · <strong>D</strong> 0-3.9
            </p>

        @else
            {{-- ═══════ GRID VIEW (default) ═══════ --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($this->library as $item)
                    <div class="card-item p-2.5 flex flex-col gap-2 relative">
                        <div class="relative">
                            @if($item->cover_image)
                                <img src="{{ $item->cover_image }}" alt="{{ $item->title }}"
                                    class="w-full aspect-[2/3] object-cover" loading="lazy" style="border-radius: 4px;">
                            @else
                                <div class="w-full aspect-[2/3] flex items-center justify-center bg-cream border border-cream-dark"
                                     style="border-radius: 4px;">
                                    <span class="font-sans text-stone text-xs">No Image</span>
                                </div>
                            @endif

                            {{-- Rating tier badge on cover --}}
                            @if($item->personal_rating)
                                @php
                                    $r = (float) $item->personal_rating;
                                    $tierLetter = $r >= 9 ? 'S' : ($r >= 7.5 ? 'A' : ($r >= 6 ? 'B' : ($r >= 4 ? 'C' : 'D')));
                                    $tierBg     = $r >= 9 ? 'bg-corn-dark' : ($r >= 7.5 ? 'bg-grass-dark' : ($r >= 6 ? 'bg-sky-dark' : ($r >= 4 ? 'bg-soil' : 'bg-berry-dark')));
                                @endphp
                                <span class="absolute top-1 left-1 {{ $tierBg }} text-white font-pixel px-1.5 py-0.5"
                                      style="border-radius: 3px; font-size: 9px;">{{ $tierLetter }}</span>
                                <span class="absolute top-1 right-1 bg-soil-dark/85 text-corn-light font-sans font-bold text-xs px-1.5 py-0.5"
                                      style="border-radius: 3px; font-size: 10px;">★ {{ $item->personal_rating }}</span>
                            @endif
                        </div>

                        <p class="font-sans font-semibold text-soil-dark text-xs leading-tight line-clamp-2">
                            {{ $item->title }}
                        </p>

                        @php
                            $statusTag = match($item->status) {
                                'plan_to'   => 'tag-sky',
                                'ongoing'   => 'tag-grass',
                                'completed' => 'tag-soil',
                                'dropped'   => 'tag-berry',
                                default     => 'tag-soil',
                            };
                        @endphp
                        <span class="{{ $statusTag }}">{{ $item->status_label }}</span>

                        <select wire:change="updateStatus({{ $item->id }}, $event.target.value)"
                            class="input-pixel py-1.5 text-xs">
                            <option value="plan_to"   {{ $item->status === 'plan_to'   ? 'selected' : '' }}>Plan to Watch</option>
                            <option value="ongoing"   {{ $item->status === 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ $item->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="dropped"   {{ $item->status === 'dropped'   ? 'selected' : '' }}>Dropped</option>
                        </select>

                        <div class="flex gap-1 mt-auto">
                            <button wire:click="startEditRating({{ $item->id }})" class="btn-ghost flex-1 py-1.5 text-xs">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                                Rate
                            </button>
                            <button wire:click="removeFromLibrary({{ $item->id }})" wire:confirm="Remove from collection?"
                                class="btn-icon text-berry">
                                <svg viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                                    <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ── Rating Modal ── --}}
        @if($editingRatingId)
            <div class="fixed inset-0 flex items-center justify-center z-50 p-4"
                 style="background: rgba(92,70,50,0.55); backdrop-filter: blur(4px);">
                <div class="panel w-full max-w-sm animate-fade-in">
                    <h3 class="section-title mb-5">✎ RATE THIS TITLE</h3>

                    <div class="mb-4">
                        <label class="field-label">Rating (0–10)</label>
                        <input wire:model="ratingInput" type="number" min="0" max="10" step="0.5" class="input-pixel">
                        @error('ratingInput')
                            <p class="font-sans text-berry text-xs mt-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="field-label">Review (optional)</label>
                        <textarea wire:model="reviewInput" rows="3" class="input-pixel"
                            placeholder="Your thoughts..."></textarea>
                    </div>

                    <div class="flex gap-2">
                        <button wire:click="saveRating" class="btn-primary">Save Rating</button>
                        <button wire:click="$set('editingRatingId', null)" class="btn-ghost">Cancel</button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- ══════════════════════════
         TAB: STATS
    ══════════════════════════ --}}
    @if($activeTab === 'stats')
        @php $stats = $this->libraryStats; @endphp

        @if(($stats['total'] ?? 0) === 0)
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" class="w-10 h-10 text-cream-dark mx-auto mb-3">
                    <path d="M3 3v18h18M7 12l4-4 4 4 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="font-pixel text-soil" style="font-size: 9px;">BELUM ADA DATA</p>
                <p class="font-sans text-stone text-sm mt-2">Tambah minimal 1 item ke koleksimu dulu, baru stats muncul</p>
            </div>
        @else
            {{-- ─── SUMMARY KARTUS ─── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
                <div class="px-4 py-3 bg-cream/50 border border-cream-dark" style="border-radius: 6px;">
                    <p class="font-sans text-soil text-xs uppercase tracking-wider">Total Item</p>
                    <p class="font-pixel text-soil-dark mt-1.5" style="font-size: 16px;">{{ $stats['total'] }}</p>
                </div>
                <div class="px-4 py-3 bg-corn-light/40 border border-corn/30" style="border-radius: 6px;">
                    <p class="font-sans text-soil text-xs uppercase tracking-wider">Sudah Di-rate</p>
                    <p class="font-pixel text-corn-dark mt-1.5" style="font-size: 16px;">
                        {{ $stats['rated_count'] }}
                        <span class="font-sans text-soil text-xs">/ {{ $stats['total'] }}</span>
                    </p>
                </div>
                <div class="px-4 py-3 bg-grass-light/30 border border-grass/30" style="border-radius: 6px;">
                    <p class="font-sans text-soil text-xs uppercase tracking-wider">Rating Rata-rata</p>
                    <p class="font-pixel text-grass-dark mt-1.5" style="font-size: 16px;">
                        @if($stats['avg_rating'] !== null)
                            ★ {{ $stats['avg_rating'] }}
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div class="px-4 py-3 bg-berry-light/30 border border-berry/30" style="border-radius: 6px;">
                    <p class="font-sans text-soil text-xs uppercase tracking-wider">Genre Favorit</p>
                    <p class="font-sans font-bold text-berry-dark mt-1.5 text-sm leading-tight">
                        @if($stats['fav_genre'])
                            {{ $stats['fav_genre'] }}
                            <span class="font-sans font-normal text-soil text-xs block mt-0.5">{{ $stats['fav_genre_count'] }} items</span>
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>

            {{-- ─── INSIGHT BANNER ─── --}}
            @if($stats['fav_genre'])
                <div class="mb-5 px-4 py-3 bg-corn-light/40 border-l-4 border-corn flex items-center gap-3" style="border-radius: 6px;">
                    <span class="text-2xl">🎯</span>
                    <p class="font-sans text-soil-dark text-sm">
                        Kamu condong ke genre <strong class="text-corn-dark">{{ $stats['fav_genre'] }}</strong>
                        — {{ $stats['fav_genre_count'] }} dari {{ $stats['total'] }} item ({{ round($stats['fav_genre_count'] / $stats['total'] * 100) }}%)
                    </p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- ─── GENRE BAR CHART ─── --}}
                @if(!empty($stats['top_genres']))
                    @php
                        $genreLabels = array_keys($stats['top_genres']);
                        $genreCounts = array_values($stats['top_genres']);
                    @endphp
                    <div class="bg-cream/30 border border-cream-dark p-4" style="border-radius: 6px;">
                        <p class="font-sans font-semibold text-soil-dark text-sm mb-3 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                                <path d="M3 3v18h18M7 14l4-4 4 4 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Top Genre
                        </p>
                        <div
                            wire:ignore
                            wire:key="genre-bar"
                            x-data='barChart({
                                data: {
                                    labels: @json($genreLabels),
                                    datasets: [{
                                        label: "Jumlah item",
                                        data: @json($genreCounts),
                                        backgroundColor: "#6BA368",
                                        borderColor: "#4E7D4C",
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    indexAxis: "y",
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        x: { beginAtZero: true, ticks: { stepSize: 1, color: "#83644A", font: { family: "Inter", size: 10 } }, grid: { color: "rgba(212,163,115,0.15)" } },
                                        y: { ticks: { color: "#5C4632", font: { family: "Inter", size: 11 } }, grid: { display: false } }
                                    }
                                }
                            })'
                            :style="`height: ${Math.max(180, {{ count($genreLabels) }} * 26)}px;`"
                        >
                            <canvas x-ref="canvas"></canvas>
                        </div>
                    </div>
                @endif

                {{-- ─── TYPE DOUGHNUT ─── --}}
                @php
                    $typeLabels = ['🎬 Movie', '📺 TV', '⛩ Anime', '📖 Manga'];
                    $typeCounts = array_values($stats['type_dist']);
                    $typeColors = ['#BE546E', '#77AADD', '#6BA368', '#E5B567'];
                @endphp
                <div class="bg-cream/30 border border-cream-dark p-4" style="border-radius: 6px;">
                    <p class="font-sans font-semibold text-soil-dark text-sm mb-3 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 3v9l8 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Distribusi Tipe Media
                    </p>
                    <div
                        wire:ignore
                        wire:key="type-doughnut"
                        x-data='doughnutChart({
                            data: {
                                labels: @json($typeLabels),
                                datasets: [{
                                    data: @json($typeCounts),
                                    backgroundColor: @json($typeColors),
                                    borderColor: "#FBF7EC",
                                    borderWidth: 2
                                }]
                            }
                        })'
                        style="height: 230px;"
                    >
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                {{-- ─── TIER DISTRIBUTION ─── --}}
                @php
                    $tierLabels = array_keys($stats['tier_dist']);
                    $tierCounts = array_values($stats['tier_dist']);
                    $tierColors = ['#C99845', '#4E7D4C', '#77AADD', '#83644A', '#BE546E', '#A9A39E'];
                @endphp
                <div class="bg-cream/30 border border-cream-dark p-4" style="border-radius: 6px;">
                    <p class="font-sans font-semibold text-soil-dark text-sm mb-3 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-corn-dark">
                            <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/>
                        </svg>
                        Sebaran Tier Rating
                    </p>
                    <div
                        wire:ignore
                        wire:key="tier-bar"
                        x-data='barChart({
                            data: {
                                labels: @json($tierLabels),
                                datasets: [{
                                    label: "Jumlah item",
                                    data: @json($tierCounts),
                                    backgroundColor: @json($tierColors),
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { ticks: { color: "#5C4632", font: { family: "Inter", size: 11, weight: "600" } }, grid: { display: false } },
                                    y: { beginAtZero: true, ticks: { stepSize: 1, color: "#83644A", font: { family: "Inter", size: 10 } }, grid: { color: "rgba(212,163,115,0.15)" } }
                                }
                            }
                        })'
                        style="height: 200px;"
                    >
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                {{-- ─── STATUS PROGRESS ─── --}}
                <div class="bg-cream/30 border border-cream-dark p-4" style="border-radius: 6px;">
                    <p class="font-sans font-semibold text-soil-dark text-sm mb-4 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                            <path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Status Tontonan
                    </p>
                    <div class="space-y-3">
                        @php
                            $statusMeta = [
                                'plan_to'   => ['label' => 'Plan to Watch', 'color' => 'sky',   'fill' => 'bg-sky-dark'],
                                'ongoing'   => ['label' => 'Sedang Berjalan', 'color' => 'grass', 'fill' => 'bg-grass-dark'],
                                'completed' => ['label' => 'Selesai',       'color' => 'soil',  'fill' => 'bg-soil'],
                                'dropped'   => ['label' => 'Drop',          'color' => 'berry', 'fill' => 'bg-berry-dark'],
                            ];
                        @endphp
                        @foreach($statusMeta as $key => $meta)
                            @php
                                $count = $stats['status_dist'][$key] ?? 0;
                                $pct   = $stats['total'] > 0 ? round($count / $stats['total'] * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1.5">
                                    <span class="font-sans text-sm font-medium text-soil-dark">{{ $meta['label'] }}</span>
                                    <span class="font-mono text-sm text-soil tabular-nums">{{ $count }} <span class="text-stone text-xs">({{ $pct }}%)</span></span>
                                </div>
                                <div class="h-2.5 bg-white border border-cream-dark overflow-hidden" style="border-radius: 999px;">
                                    <div class="h-full {{ $meta['fill'] }} transition-all duration-500"
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif
    @endif

    {{-- ── TOAST (success / error) ── --}}
    <div
        x-show="toast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-50 px-5 py-3 shadow-cozy-lg border max-w-sm"
        :class="toastVariant === 'error'
            ? 'bg-berry-dark border-berry'
            : 'bg-soil-dark border-grass-dark'"
        style="border-radius: 6px;"
    >
        <p class="font-pixel" style="font-size: 8px;"
           :class="toastVariant === 'error' ? 'text-berry-light' : 'text-grass-light'">
            <span x-text="toastVariant === 'error' ? '⚠ ERROR' : '✓ SUCCESS'"></span>
        </p>
        <p class="font-sans font-medium text-cream-light text-sm mt-1" x-text="toastMsg"></p>
    </div>
</div>

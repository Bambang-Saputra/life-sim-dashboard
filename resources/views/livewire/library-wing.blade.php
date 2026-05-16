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
    <div class="flex gap-1.5 mb-5">
        @foreach(['search' => ['🔍', 'Search'], 'collection' => ['📚', 'My Collection']] as $tab => $cfg)
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
        <form wire:submit.prevent="submitSearch" class="flex gap-2 mb-5">
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
        {{-- Filters --}}
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
        </div>

        @if($this->library->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" class="w-10 h-10 text-cream-dark mx-auto mb-3">
                    <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="font-pixel text-soil" style="font-size: 9px;">COLLECTION EMPTY</p>
                <p class="font-sans text-stone text-sm mt-2">Go to <span class="font-semibold text-grass-dark">Search</span> to add your first title</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($this->library as $item)
                    <div class="card-item p-2.5 flex flex-col gap-2">
                        @if($item->cover_image)
                            <img src="{{ $item->cover_image }}" alt="{{ $item->title }}"
                                class="w-full aspect-[2/3] object-cover" loading="lazy" style="border-radius: 4px;">
                        @else
                            <div class="w-full aspect-[2/3] flex items-center justify-center bg-cream border border-cream-dark"
                                 style="border-radius: 4px;">
                                <span class="font-sans text-stone text-xs">No Image</span>
                            </div>
                        @endif

                        <p class="font-sans font-semibold text-soil-dark text-xs leading-tight line-clamp-2">
                            {{ $item->title }}
                        </p>

                        <div class="flex items-center justify-between gap-1 text-xs">
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
                            @if($item->personal_rating)
                                <span class="font-sans font-semibold text-corn-dark text-xs flex items-center gap-0.5">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
                                    {{ $item->personal_rating }}
                                </span>
                            @endif
                        </div>

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

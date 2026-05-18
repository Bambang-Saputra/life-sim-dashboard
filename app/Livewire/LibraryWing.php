<?php

namespace App\Livewire;

use App\Models\LibraryItem;
use App\Services\TmdbService;
use App\Services\JikanService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;

class LibraryWing extends Component
{
    public string $searchQuery   = '';
    public string $searchType    = 'movie';
    public array  $searchResults = [];
    public array  $defaultItems  = [];
    public bool   $isSearching   = false;
    public bool   $isLoadingDefaults = false;
    public string $activeTab        = 'search';
    public string $filterStatus     = 'all';
    public string $filterType       = 'all';
    public string $collectionView   = 'grid'; // grid | tier
    public string $collectionSort   = 'rating_desc'; // rating_desc | rating_asc | title | latest

    // ── Pagination state ──
    public int $currentPage  = 1;
    public int $totalPages   = 1;
    public int $totalResults = 0;

    #[Rule('nullable|numeric|min:0|max:10')]
    public float $ratingInput = 0;

    #[Rule('nullable|string|max:1000')]
    public string $reviewInput = '';

    public ?int $editingRatingId = null;

    public function mount(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->loadDefaults($tmdb, $jikan);
    }

    /** Hitung mode aktif: 'search' jika ada query, 'default' kalau kosong */
    private function mode(): string
    {
        return strlen(trim($this->searchQuery)) >= 2 ? 'search' : 'default';
    }

    public function loadDefaults(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->isLoadingDefaults = true;

        try {
            $result = match($this->searchType) {
                'movie', 'tv' => $tmdb->trending($this->searchType, $this->currentPage),
                'anime'       => $jikan->topAnime($this->currentPage),
                'manga'       => $jikan->topManga($this->currentPage),
                default       => ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0],
            };

            $this->defaultItems  = $result['items']         ?? [];
            $this->totalPages    = $result['total_pages']   ?? 1;
            $this->totalResults  = $result['total_results'] ?? 0;
            $this->currentPage   = $result['current_page']  ?? 1;
        } catch (\Throwable $e) {
            $this->defaultItems = [];
            Log::error('Default items load failed', ['type' => $this->searchType, 'err' => $e->getMessage()]);
        } finally {
            $this->isLoadingDefaults = false;
        }
    }

    public function search(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->validate(['searchQuery' => 'required|min:2']);
        $this->isSearching = true;

        try {
            $result = match($this->searchType) {
                'movie', 'tv' => $tmdb->search($this->searchQuery, $this->searchType, $this->currentPage),
                'anime'       => $jikan->searchAnime($this->searchQuery, $this->currentPage),
                'manga'       => $jikan->searchManga($this->searchQuery, $this->currentPage),
                default       => ['items' => [], 'current_page' => 1, 'total_pages' => 1, 'total_results' => 0],
            };

            $this->searchResults = $result['items']         ?? [];
            $this->totalPages    = $result['total_pages']   ?? 1;
            $this->totalResults  = $result['total_results'] ?? 0;
            $this->currentPage   = $result['current_page']  ?? 1;
        } catch (\Throwable $e) {
            $this->searchResults = [];
            $this->dispatch('search-failed', message: $e->getMessage());
            Log::error('Library search failed', [
                'type' => $this->searchType, 'query' => $this->searchQuery, 'err' => $e->getMessage(),
            ]);
        } finally {
            $this->isSearching = false;
        }
    }

    /** Trigger saat user submit search baru — reset ke page 1 dulu */
    public function submitSearch(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->currentPage = 1;
        $this->search($tmdb, $jikan);
    }

    /** Auto-clear hasil + load defaults baru saat ganti tipe pencarian, reset ke page 1 */
    public function updatedSearchType(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->searchResults = [];
        $this->currentPage   = 1;
        $this->loadDefaults($tmdb, $jikan);
    }

    /** Next page — context-aware (search atau default) */
    public function nextPage(TmdbService $tmdb, JikanService $jikan): void
    {
        if ($this->currentPage >= $this->totalPages) return;
        $this->currentPage++;
        $this->reloadCurrent($tmdb, $jikan);
    }

    public function prevPage(TmdbService $tmdb, JikanService $jikan): void
    {
        if ($this->currentPage <= 1) return;
        $this->currentPage--;
        $this->reloadCurrent($tmdb, $jikan);
    }

    public function goToPage(int $page, TmdbService $tmdb, JikanService $jikan): void
    {
        $page = max(1, min($page, $this->totalPages));
        if ($page === $this->currentPage) return;
        $this->currentPage = $page;
        $this->reloadCurrent($tmdb, $jikan);
    }

    private function reloadCurrent(TmdbService $tmdb, JikanService $jikan): void
    {
        if ($this->mode() === 'search') {
            $this->search($tmdb, $jikan);
        } else {
            $this->loadDefaults($tmdb, $jikan);
        }
        // Scroll ke atas (akan di-handle di Alpine via event)
        $this->dispatch('library-page-changed');
    }

    public function addToLibrary(array $itemData): void
    {
        LibraryItem::updateOrCreate(
            ['api_type' => $itemData['api_type'], 'external_id' => $itemData['external_id']],
            [
                'title'           => $itemData['title'],
                'cover_image'     => $itemData['cover_image'] ?? null,
                'genre'           => $itemData['genre'] ?? null,
                'status'          => 'plan_to',
                'metadata'        => $itemData['metadata'] ?? null,
                'personal_rating' => null,
                'personal_review' => null,
            ]
        );

        $this->dispatch('library-item-added', title: $itemData['title']);
    }

    public function startEditRating(int $libraryItemId): void
    {
        $item = LibraryItem::findOrFail($libraryItemId);
        $this->editingRatingId = $libraryItemId;
        $this->ratingInput     = (float) ($item->personal_rating ?? 0);
        $this->reviewInput     = $item->personal_review ?? '';
    }

    public function saveRating(): void
    {
        $this->validate([
            'ratingInput' => 'required|numeric|min:0|max:10',
            'reviewInput' => 'nullable|string|max:1000',
        ]);

        LibraryItem::findOrFail($this->editingRatingId)->update([
            'personal_rating' => $this->ratingInput,
            'personal_review' => $this->reviewInput ?: null,
            'status'          => 'completed',
        ]);

        $this->editingRatingId = null;
        $this->ratingInput     = 0;
        $this->reviewInput     = '';
        $this->dispatch('rating-saved');
    }

    public function updateStatus(int $libraryItemId, string $status): void
    {
        LibraryItem::findOrFail($libraryItemId)->update(['status' => $status]);
    }

    public function removeFromLibrary(int $libraryItemId): void
    {
        LibraryItem::findOrFail($libraryItemId)->delete();
    }

    public function getLibraryProperty()
    {
        return LibraryItem::query()
            ->when($this->filterStatus !== 'all', fn($q) => $q->byStatus($this->filterStatus))
            ->when($this->filterType !== 'all',   fn($q) => $q->byType($this->filterType))
            ->when(
                $this->collectionSort === 'rating_desc',
                fn($q) => $q->orderByRaw('personal_rating IS NULL ASC')->orderByDesc('personal_rating')->latest('id'),
                function ($q) {
                    return match($this->collectionSort) {
                        'rating_asc' => $q->orderByRaw('personal_rating IS NULL DESC')->orderBy('personal_rating')->latest('id'),
                        'title'      => $q->orderBy('title'),
                        'latest'     => $q->latest('id'),
                        default      => $q->latest('id'),
                    };
                }
            )
            ->get();
    }

    /** Group library items by tier (S/A/B/C/D/Unrated) based on personal_rating */
    public function getLibraryByTierProperty(): array
    {
        $items = $this->library;

        $tiers = [
            'S' => ['min' => 9.0, 'max' => 10.0, 'label' => 'S · Masterpiece',    'color' => 'corn',  'items' => collect()],
            'A' => ['min' => 7.5, 'max' => 8.99, 'label' => 'A · Excellent',      'color' => 'grass', 'items' => collect()],
            'B' => ['min' => 6.0, 'max' => 7.49, 'label' => 'B · Good',           'color' => 'sky',   'items' => collect()],
            'C' => ['min' => 4.0, 'max' => 5.99, 'label' => 'C · Mid',            'color' => 'soil',  'items' => collect()],
            'D' => ['min' => 0.0, 'max' => 3.99, 'label' => 'D · Skip',           'color' => 'berry', 'items' => collect()],
            'U' => ['min' => null,'max' => null, 'label' => '? · Belum di-rate',  'color' => 'stone', 'items' => collect()],
        ];

        foreach ($items as $item) {
            $r = $item->personal_rating !== null ? (float) $item->personal_rating : null;
            if ($r === null)               $tiers['U']['items']->push($item);
            elseif ($r >= 9.0)             $tiers['S']['items']->push($item);
            elseif ($r >= 7.5)             $tiers['A']['items']->push($item);
            elseif ($r >= 6.0)             $tiers['B']['items']->push($item);
            elseif ($r >= 4.0)             $tiers['C']['items']->push($item);
            else                           $tiers['D']['items']->push($item);
        }

        // Sort items in each tier by rating desc
        foreach ($tiers as $k => &$tier) {
            $tier['items'] = $tier['items']->sortByDesc('personal_rating')->values();
        }

        return $tiers;
    }

    public function render()
    {
        return view('livewire.library-wing');
    }
}

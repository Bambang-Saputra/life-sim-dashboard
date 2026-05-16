<?php

namespace App\Livewire;

use App\Models\LibraryItem;
use App\Services\TmdbService;
use App\Services\JikanService;
use Livewire\Component;
use Livewire\Attributes\Rule;

class LibraryWing extends Component
{
    public string $searchQuery   = '';
    public string $searchType    = 'movie';
    public array  $searchResults = [];
    public array  $defaultItems  = []; // Trending/Top untuk default view
    public bool   $isSearching   = false;
    public bool   $isLoadingDefaults = false;
    public string $activeTab     = 'search';
    public string $filterStatus  = 'all';
    public string $filterType    = 'all';

    public function mount(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->loadDefaults($tmdb, $jikan);
    }

    /** Muat trending / top sesuai searchType (cached 6 jam di service). */
    public function loadDefaults(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->isLoadingDefaults = true;

        try {
            $this->defaultItems = match($this->searchType) {
                'movie', 'tv' => $tmdb->trending($this->searchType, 12),
                'anime'       => $jikan->topAnime(12),
                'manga'       => $jikan->topManga(12),
                default       => [],
            };
        } catch (\Throwable $e) {
            $this->defaultItems = [];
            \Illuminate\Support\Facades\Log::error('Default items load failed', [
                'type' => $this->searchType, 'err' => $e->getMessage(),
            ]);
        } finally {
            $this->isLoadingDefaults = false;
        }
    }

    #[Rule('nullable|numeric|min:0|max:10')]
    public float $ratingInput = 0;

    #[Rule('nullable|string|max:1000')]
    public string $reviewInput = '';

    public ?int $editingRatingId = null;

    public function search(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->validate(['searchQuery' => 'required|min:2']);
        $this->isSearching = true;

        try {
            $this->searchResults = match($this->searchType) {
                'movie', 'tv' => $tmdb->search($this->searchQuery, $this->searchType),
                'anime'       => $jikan->searchAnime($this->searchQuery),
                'manga'       => $jikan->searchManga($this->searchQuery),
                default       => [],
            };
        } catch (\Throwable $e) {
            $this->searchResults = [];
            $this->dispatch('search-failed', message: $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Library search failed', [
                'type'  => $this->searchType,
                'query' => $this->searchQuery,
                'err'   => $e->getMessage(),
            ]);
        } finally {
            $this->isSearching = false;
        }
    }

    /** Auto-clear hasil + load defaults baru saat ganti tipe pencarian */
    public function updatedSearchType(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->searchResults = [];
        $this->loadDefaults($tmdb, $jikan);
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
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.library-wing');
    }
}

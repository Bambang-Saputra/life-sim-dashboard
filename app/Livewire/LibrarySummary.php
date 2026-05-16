<?php

namespace App\Livewire;

use App\Models\LibraryItem;
use Livewire\Component;
use Livewire\Attributes\Computed;

class LibrarySummary extends Component
{
    #[Computed]
    public function recent()
    {
        return LibraryItem::latest()->limit(6)->get();
    }

    #[Computed]
    public function countsByType(): array
    {
        return [
            'movie' => LibraryItem::byType('movie')->count(),
            'tv'    => LibraryItem::byType('tv')->count(),
            'anime' => LibraryItem::byType('anime')->count(),
            'manga' => LibraryItem::byType('manga')->count(),
        ];
    }

    #[Computed]
    public function total(): int
    {
        return LibraryItem::count();
    }

    #[Computed]
    public function ongoingCount(): int
    {
        return LibraryItem::byStatus('ongoing')->count();
    }

    public function render()
    {
        return view('livewire.library-summary');
    }
}

<x-app-layout>
    <x-slot name="title">Library Wing - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div>
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Library Wing</h1>
                <p class="page-description">
                    Katalog personal untuk film, TV series, anime, dan manga. Browse trending content,
                    simpan ke koleksi, beri rating, dan lihat pola tontonan favoritmu.
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('quests.index') }}" class="btn-ghost">Open Quests</a>
                <a href="{{ route('finance.index') }}" class="btn-ghost">Open Finance</a>
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

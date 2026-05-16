<x-app-layout>
    <x-slot name="title">Library Wing · Life-Sim Dashboard</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('dashboard') }}" class="font-sans text-xs text-soil hover:text-grass-dark no-underline">← Dashboard</a>
        </div>
        <h1 class="font-pixel text-soil-dark flex items-center gap-3" style="font-size: 14px; letter-spacing: 0.05em;">
            <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-sky-dark">
                <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11M9 4v6M15 4v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            LIBRARY WING
        </h1>
        <p class="font-sans text-soil text-sm mt-2">Koleksi film, TV series, anime, dan manga — search, rate, dan lacak status nontonmu.</p>
    </div>

    {{-- API Status Notice --}}
    @if(empty(config('services.tmdb.api_key')) || config('services.tmdb.api_key') === 'your_tmdb_api_key_here')
        <div class="mb-5 panel p-4 border-l-4 !border-l-corn">
            <p class="font-sans font-semibold text-soil-dark text-sm mb-1">⚠ TMDB API Key belum di-setup</p>
            <p class="font-sans text-soil text-sm">
                Search untuk <strong>Movie</strong> dan <strong>TV Series</strong> belum bisa dipakai sampai kamu dapatkan API key gratis dari
                <a href="https://www.themoviedb.org/settings/api" target="_blank" class="text-grass-dark font-semibold underline">themoviedb.org/settings/api</a>.
                Lalu masukkan ke <code class="bg-cream px-1.5 py-0.5 text-xs">TMDB_API_KEY=</code> di file <code class="bg-cream px-1.5 py-0.5 text-xs">.env</code>.
                <br><br>
                <strong>Anime</strong> dan <strong>Manga</strong> tetap bisa dipakai (pakai Jikan API, gratis tanpa key).
            </p>
        </div>
    @endif

    @livewire('library-wing')

</x-app-layout>

<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-sky-dark">
                <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M4 19V8h16v11M9 4v6M15 4v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            LIBRARY WING
        </h2>
        <a href="{{ route('library.index') }}" class="font-sans text-xs text-grass-dark hover:text-grass font-semibold no-underline">Explore →</a>
    </div>

    {{-- Counts by type --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 mb-4">
        <div class="px-3 py-2 border border-cream-dark bg-white text-center" style="border-radius: 6px;">
            <p class="font-pixel text-soil-dark leading-none" style="font-size: 14px;">{{ $this->total }}</p>
            <p class="font-sans text-soil text-xs mt-1">Total</p>
        </div>
        <div class="px-3 py-2 border border-cream-dark bg-cream/40 text-center" style="border-radius: 6px;">
            <p class="font-pixel text-berry-dark leading-none" style="font-size: 13px;">🎬 {{ $this->countsByType['movie'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Movie</p>
        </div>
        <div class="px-3 py-2 border border-cream-dark bg-cream/40 text-center" style="border-radius: 6px;">
            <p class="font-pixel text-sky-dark leading-none" style="font-size: 13px;">📺 {{ $this->countsByType['tv'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">TV</p>
        </div>
        <div class="px-3 py-2 border border-cream-dark bg-cream/40 text-center" style="border-radius: 6px;">
            <p class="font-pixel text-grass-dark leading-none" style="font-size: 13px;">⛩ {{ $this->countsByType['anime'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Anime</p>
        </div>
        <div class="px-3 py-2 border border-cream-dark bg-cream/40 text-center" style="border-radius: 6px;">
            <p class="font-pixel text-corn-dark leading-none" style="font-size: 13px;">📖 {{ $this->countsByType['manga'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Manga</p>
        </div>
    </div>

    {{-- Recent items horizontal scroll --}}
    <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">
        Baru Ditambahkan
        @if($this->ongoingCount)
            <span class="text-grass-dark font-normal normal-case ml-1">· {{ $this->ongoingCount }} sedang berjalan</span>
        @endif
    </p>

    @if($this->recent->isEmpty())
        <div class="empty-state py-8">
            <p class="font-sans text-stone text-sm">Belum ada koleksi.</p>
            <a href="{{ route('library.index') }}" class="font-sans text-grass-dark text-sm font-semibold no-underline mt-2 inline-block">+ Tambah Item →</a>
        </div>
    @else
        <div class="flex gap-3 overflow-x-auto pb-2">
            @foreach($this->recent as $item)
                <a href="{{ route('library.index') }}" class="flex-shrink-0 w-24 no-underline">
                    @if($item->cover_image)
                        <img src="{{ $item->cover_image }}" alt="{{ $item->title }}"
                            class="w-full aspect-[2/3] object-cover border border-cream-dark"
                            style="border-radius: 4px;" loading="lazy">
                    @else
                        <div class="w-full aspect-[2/3] flex items-center justify-center bg-cream border border-cream-dark" style="border-radius: 4px;">
                            <span class="font-sans text-stone text-xs">No Image</span>
                        </div>
                    @endif
                    <p class="font-sans text-xs font-medium text-soil-dark mt-1.5 line-clamp-2 leading-tight">
                        {{ \Illuminate\Support\Str::limit($item->title, 22) }}
                    </p>
                </a>
            @endforeach
        </div>
    @endif
</div>

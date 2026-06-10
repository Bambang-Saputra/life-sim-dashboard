<x-app-layout>
    <x-slot name="title">Quest Board - Life-Sim Dashboard</x-slot>

    <section class="page-hero">
        <div class="page-hero-content">
            <div>
                <a href="{{ route('dashboard') }}" class="page-kicker no-underline">
                    <span>Back to Dashboard</span>
                </a>
                <h1 class="page-title">Quest Board</h1>
                <p class="page-description">
                    Ruang kerja utama untuk mengelola tugas hidup harian: prioritas, progress, deadline,
                    alarm, catatan dampak, dan history setiap quest.
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('finance.index') }}" class="btn-ghost">Open Finance</a>
                <a href="{{ route('library.index') }}" class="btn-ghost">Open Library</a>
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

    @livewire('quest-board')
</x-app-layout>

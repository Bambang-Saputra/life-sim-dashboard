<x-app-layout>
    <x-slot name="title">Quest Board · Life-Sim Dashboard</x-slot>

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('dashboard') }}" class="font-sans text-xs text-soil hover:text-grass-dark no-underline">← Dashboard</a>
        </div>
        <h1 class="font-pixel text-soil-dark flex items-center gap-3" style="font-size: 14px; letter-spacing: 0.05em;">
            <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-grass-dark">
                <path d="M14.7 6.3L7 14l-2 5 5-2 7.7-7.7-3-3z M11 21h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            QUEST BOARD
        </h1>
        <p class="font-sans text-soil text-sm mt-2">Kelola semua tugas, history, prioritas, dan progress dengan detail lengkap.</p>
    </div>

    @livewire('quest-board')

</x-app-layout>

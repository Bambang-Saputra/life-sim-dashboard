<div class="panel h-full flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <h2 class="section-title">
            <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4 text-grass-dark">
                <path d="M14.7 6.3L7 14l-2 5 5-2 7.7-7.7-3-3z M11 21h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            QUEST BOARD
        </h2>
        <a href="{{ route('quests.index') }}" class="font-sans text-xs text-grass-dark hover:text-grass font-semibold no-underline">View All →</a>
    </div>

    {{-- Mini stats --}}
    <div class="grid grid-cols-4 gap-2 mb-4">
        <div class="text-center px-2 py-2 bg-grass-light/30 border border-grass/25" style="border-radius: 6px;">
            <p class="font-pixel text-grass-dark leading-none" style="font-size: 14px;">{{ $this->stats['total_active'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Active</p>
        </div>
        <div class="text-center px-2 py-2 bg-sky-light/30 border border-sky/25" style="border-radius: 6px;">
            <p class="font-pixel text-sky-dark leading-none" style="font-size: 14px;">{{ $this->stats['done_today'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Done</p>
        </div>
        <div class="text-center px-2 py-2 bg-berry-light/30 border border-berry/25" style="border-radius: 6px;">
            <p class="font-pixel text-berry-dark leading-none" style="font-size: 14px;">{{ $this->stats['overdue'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Late</p>
        </div>
        <div class="text-center px-2 py-2 bg-corn-light/40 border border-corn/25" style="border-radius: 6px;">
            <p class="font-pixel text-corn-dark leading-none" style="font-size: 14px;">{{ $this->stats['important'] }}</p>
            <p class="font-sans text-soil text-xs mt-1">Penting</p>
        </div>
    </div>

    {{-- Top quests --}}
    <div class="space-y-2 flex-1">
        <p class="font-sans font-semibold text-soil text-xs uppercase tracking-wider mb-2">Quest Aktif Teratas</p>

        @forelse($this->topQuests as $quest)
            <div class="card-item p-3 priority-{{ $quest->priority }}">
                <div class="flex items-start gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-1">
                            @if($quest->is_important)
                                <span title="Penting" class="text-corn-dark text-xs">★</span>
                            @endif
                            <p class="font-sans font-semibold text-soil-dark text-sm truncate">{{ $quest->title }}</p>
                        </div>

                        <div class="flex items-center gap-2 mb-1.5 text-xs">
                            @if($quest->category)
                                <span class="tag-soil">{{ $quest->category }}</span>
                            @endif
                            @if($quest->due_at)
                                <span class="font-sans {{ $quest->is_overdue ? 'text-berry font-semibold' : 'text-stone' }}">
                                    ⏰ {{ $quest->due_at->diffForHumans(['short' => true]) }}
                                </span>
                            @endif
                        </div>

                        {{-- Progress bar --}}
                        <div class="flex items-center gap-2">
                            <div class="progress-quest flex-1">
                                <div class="progress-quest-fill" style="width: {{ $quest->progress }}%;"></div>
                            </div>
                            <span class="font-mono text-xs text-soil tabular-nums" style="min-width: 30px; text-align: right;">{{ $quest->progress }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state py-8">
                <p class="font-sans text-stone text-sm">Belum ada quest aktif.</p>
                <a href="{{ route('quests.index') }}" class="font-sans text-grass-dark text-sm font-semibold no-underline mt-2 inline-block">+ Buat Quest →</a>
            </div>
        @endforelse
    </div>
</div>

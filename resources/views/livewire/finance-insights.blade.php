<div>
    @if(count($this->insights))
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mb-5">
            @foreach($this->insights as $insight)
                <div class="insight-card insight-{{ $insight['tone'] }}">
                    <div class="flex items-start gap-2.5">
                        <span class="text-xl leading-none flex-shrink-0">{{ $insight['icon'] }}</span>
                        <div class="min-w-0">
                            <p class="font-sans font-bold text-soil-dark text-sm leading-snug">{{ $insight['title'] }}</p>
                            <p class="font-sans text-soil text-xs mt-1 leading-relaxed">{{ $insight['body'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

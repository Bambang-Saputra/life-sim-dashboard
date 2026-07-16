@props(['variant' => null, 'title' => null])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    @if($variant)
        <x-empty-art :variant="$variant"/>
    @endif
    @if($title)
        <p class="font-pixel text-soil {{ $variant ? 'mt-4' : '' }}" style="font-size: 9px;">{{ $title }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="font-sans text-stone text-sm mt-2 leading-relaxed">{{ $slot }}</div>
    @endif
</div>

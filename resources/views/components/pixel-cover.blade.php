@props(['type' => 'movie', 'title' => ''])

@php
    $c = [
        'movie' => ['#9A3F56', '#BE546E'],
        'tv'    => ['#5588BB', '#77AADD'],
        'anime' => ['#4E7D4C', '#6BA368'],
        'manga' => ['#C99845', '#E5B567'],
    ][$type] ?? ['#83644A', '#A88B6E'];
@endphp

{{-- Cover pengganti bergaya pixel saat item tidak punya poster --}}
<div {{ $attributes->merge(['class' => 'w-full aspect-[2/3] relative overflow-hidden border border-cream-dark']) }}
     style="border-radius: 4px; background: {{ $c[0] }};">
    <span class="absolute inset-x-0 top-0" style="height:36%; background:{{ $c[1] }};"></span>
    <span class="px-dither" style="top:calc(36% - 4px); --da:{{ $c[1] }}; --db:{{ $c[0] }};"></span>
    <span class="absolute inset-0 flex items-center justify-center font-pixel"
          style="font-size: 16px; color: #FBF7EC; text-shadow: 2px 2px 0 rgba(0,0,0,.3);">
        {{ strtoupper(mb_substr(trim($title) ?: '?', 0, 1)) }}
    </span>
</div>

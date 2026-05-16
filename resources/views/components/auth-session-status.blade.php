@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-grass-light/40 border border-grass/40 text-grass-dark font-sans text-sm px-3 py-2']) }}
         style="border-radius: 4px;">
        ✓ {{ $status }}
    </div>
@endif

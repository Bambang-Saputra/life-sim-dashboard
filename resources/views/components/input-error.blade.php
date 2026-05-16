@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'font-sans text-xs text-berry-dark space-y-0.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-1">
                <span aria-hidden="true">⚠</span>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif

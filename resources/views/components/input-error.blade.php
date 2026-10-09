@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs font-semibold text-spray-700 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-1.5">
                <span class="mt-1 inline-block w-1.5 h-1.5 bg-spray-600 rotate-45 shrink-0"></span>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif

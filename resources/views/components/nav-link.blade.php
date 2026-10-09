@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-spray-400 text-sm font-display uppercase tracking-wider leading-5 text-white focus-street transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-display uppercase tracking-wider leading-5 text-concrete-300 hover:text-white hover:border-concrete-500 focus-street transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

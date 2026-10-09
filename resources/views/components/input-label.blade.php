@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-display uppercase tracking-wide text-xs text-asphalt-800']) }}>
    {{ $value ?? $slot }}
</label>

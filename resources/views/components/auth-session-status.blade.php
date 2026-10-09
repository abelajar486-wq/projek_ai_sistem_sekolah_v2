@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'tag-street bg-acid-200 text-acid-900 mb-4']) }}>
        {{ $status }}
    </div>
@endif

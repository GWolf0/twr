@props([
    'href' => '#',
    'target' => null,
])

@php
    $isExternal = $target === '_blank';
@endphp

<a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif
    @if ($isExternal) rel="noopener noreferrer" @endif
    {{ $attributes->merge([
        'class' => 'text-primary hover:underline underline-offset-4 transition',
    ]) }}>
    {{ $slot }}
</a>

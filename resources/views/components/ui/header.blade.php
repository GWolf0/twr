@props([
    'h' => 2, // 1 | 2 | 3
])

@php
    $tag = 'h' . $h;

    $sizes = [
        'h3' => 'text-lg font-semibold',
        'h2' => 'text-2xl font-semibold',
        'h1' => 'text-4xl font-bold',
    ];
@endphp

<{{ $tag }}
    {{ $attributes->merge([
        'class' => ($sizes[$size] ?? $sizes['h2']) . ' text-foreground',
    ]) }}>
    {{ $slot }}

</{{ $tag }}>

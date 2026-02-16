@props([
    'orientation' => 'horizontal', // horizontal | vertical
])

@php
    $base = 'bg-border shrink-0';

    $classes = $orientation === 'vertical' ? "$base w-px h-full" : "$base h-px w-full";
@endphp

<div role="separator" {{ $attributes->merge(['class' => $classes]) }}></div>

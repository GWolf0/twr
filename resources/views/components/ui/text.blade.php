@props([
    'size' => 'md', // sm | md | lg
    'muted' => false,
])

@php
    $sizes = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
    ];
@endphp

<p
    {{ $attributes->merge([
        'class' => ($sizes[$size] ?? 'text-base') . ' ' . ($muted ? 'text-muted-foreground' : 'text-foreground'),
    ]) }}>
    {{ $slot }}
</p>

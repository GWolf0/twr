@props([
    'variant' => 'default', // default | secondary | success | warning | destructive | outline
    'size' => 'md', // sm | md | lg
])

@php
    $base =
        'inline-flex items-center rounded-full font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/40';

    $variants = [
        'default' => 'bg-primary text-primary-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground',
        'success' => 'bg-accent text-accent-foreground',
        'warning' => 'bg-muted text-foreground',
        'destructive' => 'bg-destructive text-destructive-foreground',
        'outline' => 'border border-border text-foreground bg-transparent',
    ];

    $sizes = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-xs px-2.5 py-1',
        'lg' => 'text-sm px-3 py-1.5',
    ];

    $classes = implode(' ', [$base, $variants[$variant] ?? $variants['default'], $sizes[$size] ?? $sizes['md']]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

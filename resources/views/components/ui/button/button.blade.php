@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base = "inline-flex items-center justify-center gap-2 rounded-md font-medium transition-colors
         focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2
         disabled:opacity-50 disabled:pointer-events-none";

    $variants = [
        'primary' => 'bg-primary text-primary-foreground hover:opacity-90 shadow-sm',
        'secondary' => 'bg-secondary text-secondary-foreground hover:opacity-90 shadow-sm',
        'outline' => 'border border-border bg-background text-foreground hover:bg-muted',
        'ghost' => 'text-foreground hover:bg-muted',
        'link' => 'text-primary underline-offset-4 hover:underline',
        'destructive' => 'bg-destructive text-destructive-foreground hover:opacity-90 shadow-sm',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-xs',
        'md' => 'h-9 px-4 text-sm',
        'lg' => 'h-11 px-8 text-base',
        'icon-sm' => 'h-8 w-8',
        'icon-md' => 'h-9 w-9',
        'icon-lg' => 'h-11 w-11',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>

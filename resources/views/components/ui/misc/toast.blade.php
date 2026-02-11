<!-- Template for reference only, this component will be created by javascript -->
@props([
    'message' => null,
    'severity' => 'info',
    'icon' => null,
])

@php
    $base = "w-full rounded-md border p-4 shadow-lg 
         flex items-start gap-3 text-sm 
         transition-all duration-200 ease-out";

    $variants = [
        'info' => 'bg-secondary text-secondary-foreground border-border',
        'success' => 'bg-accent text-accent-foreground border-border',
        'warning' => 'bg-muted text-foreground border-border',
        'error' => 'bg-destructive text-destructive-foreground border-destructive',
    ];

    $classes = $base . ' ' . ($variants[$severity] ?? $variants['info']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <i class="{{ $icon }} text-base mt-0.5"></i>
    @endif

    <div class="flex-1">
        {{ $message }}
    </div>
</div>

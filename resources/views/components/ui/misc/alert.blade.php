@props([
    'message' => null,
    'severity' => 'info', // info | warning | error | success
    'icon' => null,
    'closeBtn' => true,
    'autoclose' => false,
])

@php
    $base = 'relative w-full rounded-md border p-4 pr-10 text-sm shadow-sm transition-opacity duration-300';

    $variants = [
        'info' => 'bg-secondary text-secondary-foreground border-border',
        'success' => 'bg-accent text-accent-foreground border-border',
        'warning' => 'bg-muted text-foreground border-border',
        'error' => 'bg-destructive text-destructive-foreground border-destructive',
    ];

    $classes = $base . ' ' . ($variants[$severity] ?? $variants['info']);

    $icons = [
        'info' => 'bi bi-info-circle',
        'success' => 'bi bi-check-circle',
        'warning' => 'bi bi-exclamation-triangle',
        'error' => 'bi bi-x-circle',
    ];

    $iconClass = $icon ?? ($icons[$severity] ?? null);
@endphp

<div data-role="alert" data-autoclose="{{ $autoclose ? 'true' : 'false' }}"
    {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-start gap-3">

        @if ($iconClass)
            <i class="{{ $iconClass }} text-base mt-0.5"></i>
        @endif

        <div class="flex-1">
            {{ $slot ?? $message }}
        </div>

        @if ($closeBtn)
            <button type="button" data-role="alert-close"
                class="absolute right-3 top-3 text-current opacity-70 hover:opacity-100 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        @endif

    </div>
</div>

@props([
    'alignX' => 'right', // left | center | right
    'alignY' => 'bottom', // top | bottom
])

@php
    $alignmentX = match ($alignX) {
        'left' => 'left-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default => 'right-0',
    };

    $alignmentY = match ($alignY) {
        'top' => 'bottom-full mb-2',
        default => 'top-full mt-2',
    };

@endphp

<div class="relative inline-block" data-role="dropdown">

    {{-- Trigger --}}
    <div data-role="dropdown-trigger">
        {{ $trigger }}
    </div>

    {{-- Content --}}
    <div data-role="dropdown-content"
        class="absolute z-50 min-w-48 {{ $alignmentX }} {{ $alignmentY }}
               hidden opacity-0 scale-95
               rounded-md border border-border bg-card text-card-foreground
               shadow-lg transition-all duration-150 ease-out">
        <div>
            {{ $content }}
        </div>
    </div>

</div>

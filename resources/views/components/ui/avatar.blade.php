@props([
    'src' => null,
    'alt' => 'Avatar',
    'name' => null, // used for fallback initial
    'size' => 'md', // sm | md | lg | xl
    'rounded' => 'full', // full | md
])

@php
    use Illuminate\Support\Str;

    $sizes = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-14 w-14 text-base',
        'xl' => 'h-20 w-20 text-lg',
    ];

    $radius = [
        'full' => 'rounded-full',
        'md' => 'rounded-md',
    ];

    $base = 'inline-flex items-center justify-center overflow-hidden bg-muted text-muted-foreground font-medium';

    $classes = implode(' ', [$base, $sizes[$size] ?? $sizes['md'], $radius[$rounded] ?? $radius['full']]);

    $initial = $name ? strtoupper(Str::substr($name, 0, 1)) : null;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt }}" class="h-full w-full object-cover" />
    @elseif ($initial)
        <span>{{ $initial }}</span>
    @else
        <i class="bi bi-person"></i>
    @endif
</div>

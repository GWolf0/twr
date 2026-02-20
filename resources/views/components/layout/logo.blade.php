@props([
    'size' => 'md', // sm | md | lg
    'linkToHomePage' => false,
])

@php
    $sizePX = [
        'sm' => 32,
        'md' => 48,
        'lg' => 64,
        'xl' => 128,
    ];

    $width = $sizePX[$size] ?? $sizePX['md'];
    $logo = asset('logo_raw.png');
@endphp

<div class="rounded-md border-2 border-border">
    @if ($linkToHomePage)
        <a href="{{ url('/') }}" class="inline-block">
            <img src="{{ $logo }}" width="{{ $width }}" alt="{{ config('app.name') }} Logo"
                class="rounded-md overflow-hidden" />
        </a>
    @else
        <img src="{{ $logo }}" width="{{ $width }}" alt="{{ config('app.name') }} Logo"
            class="rounded-md overflow-hidden" />
    @endif
</div>

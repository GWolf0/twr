@props([
    'size' => 'md', // sm | md | lg
    'linkToHomePage' => false,
])

@php
    $sizePX = [
        'sm' => 32,
        'md' => 64,
        'lg' => 128,
    ];

    $width = $sizePX[$size] ?? $sizePX['md'];
    $logo = asset('logo_raw.png');
@endphp

@if ($linkToHomePage)
    <a href="{{ url('/') }}" class="inline-block">
        <img src="{{ $logo }}" width="{{ $width }}" alt="{{ config('app.name') }} Logo"
            class="rounded-md overflow-hidden" />
    </a>
@else
    <img src="{{ $logo }}" width="{{ $width }}" alt="{{ config('app.name') }} Logo"
        class="rounded-md overflow-hidden" />
@endif

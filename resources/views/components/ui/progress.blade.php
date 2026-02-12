@props([
    'value' => 0,
    'max' => 100,
])

@php
    $percentage = 0;

    if ($max > 0) {
        $percentage = min(100, max(0, ($value / $max) * 100));
    }
@endphp

<div {{ $attributes->merge([
    'class' => 'relative w-full h-3 bg-muted rounded-full overflow-hidden',
]) }}
    role="progressbar" aria-valuemin="0" aria-valuemax="{{ $max }}" aria-valuenow="{{ $value }}">
    <div class="h-full bg-primary transition-all duration-300 ease-in-out" style="width: {{ $percentage }}%;"></div>
</div>

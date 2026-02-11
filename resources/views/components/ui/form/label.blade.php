@props([
    'for' => null,
])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge([
        'class' => 'text-sm font-medium text-foreground'
    ]) }}
>
    {{ $slot }}
</label>

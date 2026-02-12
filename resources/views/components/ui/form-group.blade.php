@props([
    'inline' => false,
])

<div
    {{ $attributes->merge([
        'class' => $inline ? 'flex items-start gap-4' : 'flex flex-col gap-2',
    ]) }}>
    {{ $slot }}
</div>


@props([
    'padding' => 'md', // sm | md | lg | none
    'shadow' => true,
    'rounded' => 'lg', // sm | md | lg | xl | none
])

@php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-3',
        'md' => 'p-5',
        'lg' => 'p-8',
    ];

    $roundedClasses = [
        'none' => '',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
    ];
@endphp

<div
    {{ $attributes->merge([
        'class' =>
            'bg-card text-card-foreground ' .
            ($paddingClasses[$padding] ?? 'p-5') .
            ' ' .
            ($roundedClasses[$rounded] ?? 'rounded-lg') .
            ' ' .
            ($shadow ? 'shadow-sm' : ''),
    ]) }}>
    {{ $slot }}
</div>

@props([
    'name' => null,
    'type' => 'text',
])

<input type="{{ $type }}" @if ($name) name="{{ $name }}" @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-md border border-border bg-card px-3 py-2 text-sm text-foreground shadow-sm transition-colors
                        placeholder:text-muted-foreground
                        focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                        disabled:opacity-50 disabled:cursor-not-allowed',
    ]) }} />

@props([
    'name' => null,
    'options' => [],
])

<select @if ($name) name="{{ $name }}" @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-md border border-border bg-card px-3 py-2 text-sm text-foreground shadow-sm transition-colors
                        focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                        disabled:opacity-50 disabled:cursor-not-allowed',
    ]) }}>
    @foreach ($options as $label => $value)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>

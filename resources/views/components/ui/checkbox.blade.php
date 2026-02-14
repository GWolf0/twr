@props([
    'name' => null,
    'label' => null,
])

<div class="flex items-center gap-2">
    <input type="checkbox" id="{{ $name }}" @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => 'h-4 w-4 rounded border-border bg-card text-primary accent-primary
                                focus:ring-2 focus:ring-primary focus:ring-offset-1
                                disabled:opacity-50 disabled:cursor-not-allowed',
        ]) }} />

    @if ($label)
        <x-ui.label :for="$name" class="cursor-pointer">
            {{ $label }}
        </x-ui.label>
    @endif
</div>

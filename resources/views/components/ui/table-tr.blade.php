<tr {{ $attributes->merge([
    'class' => 'hover:bg-muted/30 transition-colors',
]) }}>
    {{ $slot }}
</tr>

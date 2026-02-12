@props([
    'caption' => null,
])

<div class="w-full overflow-x-auto">
    <table
        {{ $attributes->merge([
            'class' => 'w-full border-collapse text-sm bg-card rounded-lg overflow-hidden',
        ]) }}>

        @if ($caption)
            <caption class="text-left text-muted-foreground text-sm p-4">
                {{ $caption }}
            </caption>
        @endif

        {{-- Header --}}
        @isset($header)
            <thead class="bg-muted/40 border-b border-border">
                {{ $header }}
            </thead>
        @endisset

        {{-- Body --}}
        <tbody class="divide-y divide-border">
            {{ $slot }}
        </tbody>

        {{-- Footer --}}
        @isset($footer)
            <tfoot class="bg-muted/30 border-t border-border">
                {{ $footer }}
            </tfoot>
        @endisset

    </table>
</div>

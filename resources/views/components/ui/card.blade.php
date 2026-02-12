@props([
    'divider' => true,
])

<x-ui.paper {{ $attributes }}>

    @isset($header)
        <div class="mb-4">
            {{ $header }}
        </div>

        @if ($divider && isset($content))
            <div class="border-b border-border mb-4"></div>
        @endif
    @endisset

    @isset($content)
        <div class="space-y-4">
            {{ $content }}
        </div>
    @endisset

    @isset($footer)
        @if ($divider)
            <div class="border-t border-border mt-4 pt-4"></div>
        @endif

        <div class="mt-4">
            {{ $footer }}
        </div>
    @endisset

</x-ui.paper>

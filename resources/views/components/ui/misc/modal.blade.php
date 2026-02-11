@props([
    'id',
    'width' => 'md', // sm | md | lg | xl | full
])

@php
    $widthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        'full' => 'max-w-3xl',
    ];

    $modalWidth = $widthClasses[$width] ?? $widthClasses['md'];
@endphp

<div id="{{ $id }}" data-role="modal"
    class="hidden w-full {{ $modalWidth }}
           opacity-0 scale-95
           transition-all duration-200 ease-out">
    <div class="relative rounded-lg border border-border bg-card text-card-foreground shadow-xl">

        {{-- Header --}}
        @isset($header)
            <div class="flex items-center justify-between border-b border-border px-6 py-4">
                <div class="text-lg font-semibold">
                    {{ $header }}
                </div>

                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="text-muted-foreground hover:text-foreground transition">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endisset

        {{-- Content --}}
        <div class="px-6 py-4">
            {{ $content ?? ($slot ?? '') }}
        </div>

        {{-- Footer --}}
        @isset($footer)
            <div class="border-t border-border px-6 py-4 flex justify-end gap-2">
                {{ $footer }}
            </div>
        @endisset

    </div>
</div>

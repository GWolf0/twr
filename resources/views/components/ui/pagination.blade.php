@props([
    'currentPage' => 1,
    'lastPage' => 1,
    'baseUrl' => null,
    'query' => [],
    'window' => 2, // how many pages around current
])

@php
    if ($lastPage <= 1) {
        return;
    }

    $baseUrl = $baseUrl ?? request()->url();

    $buildUrl = function ($page) use ($baseUrl, $query) {
        return $baseUrl . '?' . http_build_query(array_merge($query, ['page' => $page]));
    };

    $start = max(1, $currentPage - $window);
    $end = min($lastPage, $currentPage + $window);
@endphp

<nav role="navigation" aria-label="Pagination Navigation">
    <ul class="flex items-center justify-center gap-1">

        {{-- Previous --}}
        @if ($currentPage > 1)
            <li>
                <a href="{{ $buildUrl($currentPage - 1) }}"
                    class="px-3 py-2 text-sm rounded-md border bg-background hover:bg-muted transition">
                    Previous
                </a>
            </li>
        @endif

        {{-- First page --}}
        @if ($start > 1)
            <li>
                <a href="{{ $buildUrl(1) }}"
                    class="px-3 py-2 text-sm rounded-md border bg-background hover:bg-muted transition">
                    1
                </a>
            </li>

            @if ($start > 2)
                <li class="px-2 text-muted-foreground text-sm">...</li>
            @endif
        @endif

        {{-- Windowed Pages --}}
        @for ($i = $start; $i <= $end; $i++)
            <li>
                <a href="{{ $buildUrl($i) }}"
                    class="
                        px-3 py-2 text-sm rounded-md border transition
                        {{ $i == $currentPage ? 'bg-primary text-primary-foreground border-primary' : 'bg-background hover:bg-muted' }}
                   ">
                    {{ $i }}
                </a>
            </li>
        @endfor

        {{-- Last page --}}
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <li class="px-2 text-muted-foreground text-sm">...</li>
            @endif

            <li>
                <a href="{{ $buildUrl($lastPage) }}"
                    class="px-3 py-2 text-sm rounded-md border bg-background hover:bg-muted transition">
                    {{ $lastPage }}
                </a>
            </li>
        @endif

        {{-- Next --}}
        @if ($currentPage < $lastPage)
            <li>
                <a href="{{ $buildUrl($currentPage + 1) }}"
                    class="px-3 py-2 text-sm rounded-md border bg-background hover:bg-muted transition">
                    Next
                </a>
            </li>
        @endif

    </ul>
</nav>

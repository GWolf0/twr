<!-- Check comment at the bottom for info about this component -->

@props([
    'desc' => [
        'items' => [],
        'mainItem' => null,
    ],
])

@php
    use Illuminate\Support\Str;

    $items = $desc['items'] ?? [];
    $mainItemName = $desc['mainItem'] ?? null;

    $mainItem = null;
    $filterItems = [];

    foreach ($items as $item) {
        if (($item['name'] ?? null) === $mainItemName) {
            $mainItem = $item;
        } else {
            $filterItems[] = $item;
        }
    }

    $filterFieldNames = collect($items)->pluck('name')->toArray();

    // extracting op and value from query (not to confuse with op defined in the item itself)
    function parseQueryValue($name, $op = null)
    {
        $value = request()->query($name);
        if (!$value) {
            return [null, null];
        }

        if ($op && Str::contains($value, '_')) {
            [$operator, $val] = explode('_', $value, 2);
            return [$operator, $val];
        }

        return [null, $value];
    }
@endphp

<x-ui.paper>
    <form method="GET" action="{{ url()->current() }}" id="searchFiltersForm">

        {{-- Preserve non-filter params except page --}}
        @foreach (request()->query() as $key => $value)
            @if (!in_array($key, $filterFieldNames) && $key !== 'page')
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        {{-- Top Row --}}
        <div class="flex flex-col md:flex-row md:items-center gap-3">

            {{-- Main Search --}}
            @if ($mainItem)
                @php
                    [$op, $value] = parseQueryValue($mainItem['name'], $mainItem['op'] ?? null);
                    $type = explode(':', $mainItem['type'] ?? 'input:text')[1] ?? 'text';
                @endphp

                <div class="flex-1">
                    <input type="{{ $type }}" name="{{ $mainItem['name'] }}" value="{{ $value }}"
                        {!! $mainItem['attrs'] ?? '' !!} data-op="{{ $mainItem['op'] ?? '' }}"
                        class="w-full bg-background border border-border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:outline-none"
                        placeholder="Search...">
                </div>
            @endif

            {{-- Search Button --}}
            <x-ui.button type="submit" size="icon-md" title="search">
                <i class="bi bi-search"></i>
            </x-ui.button>
            {{-- <button type="submit"
                class="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition">
                Search
            </button> --}}

            {{-- Toggle Filters --}}
            @if (count($filterItems))
                {{-- <button type="button" id="toggleFiltersBtn"
                    class="border border-border bg-secondary text-secondary-foreground px-4 py-2 rounded-md text-sm hover:bg-muted transition">
                    Filters
                </button> --}}
                <x-ui.button id="toggleFiltersBtn" variant="outline" size="icon-md" title="filters">
                    <i class="bi bi-funnel"></i>
                </x-ui.button>
            @endif

        </div>

        {{-- Filters Panel --}}
        @if (count($filterItems))
            <div id="filtersPanel" class="{{ count(request()->query()) < 2 ? 'hidden' : '' }} mt-6 space-y-4">

                @for ($i = 0; $i < count($filterItems); $i++)
                    @php
                        $item = $filterItems[$i];
                        $nextItem = $filterItems[$i + 1] ?? null;
                        $isInline = $item['inline'] ?? false;
                    @endphp

                    @if ($isInline && $nextItem)
                        <div class="flex gap-3">
                            @foreach ([$item, $nextItem] as $inlineItem)
                                @php
                                    [$op, $value] = parseQueryValue($inlineItem['name'], $inlineItem['op'] ?? null);
                                    $typeParts = explode(':', $inlineItem['type']);
                                    $baseType = $typeParts[0] ?? 'input';
                                    $inputType = $typeParts[1] ?? 'text';
                                @endphp

                                <div class="flex-1">
                                    @if ($baseType === 'input')
                                        <input type="{{ $inputType }}" name="{{ $inlineItem['name'] }}"
                                            value="{{ $value }}" {!! $inlineItem['attrs'] ?? '' !!}
                                            data-op="{{ $inlineItem['op'] ?? '' }}"
                                            class="w-full bg-background border border-border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:outline-none">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @php $i++; @endphp
                    @else
                        @php
                            [$op, $value] = parseQueryValue($item['name'], $item['op'] ?? null);
                            $typeParts = explode(':', $item['type']);
                            $baseType = $typeParts[0] ?? 'input';
                            $inputType = $typeParts[1] ?? 'text';
                        @endphp

                        <div>
                            @if ($baseType === 'input')
                                <input type="{{ $inputType }}" name="{{ $item['name'] }}"
                                    value="{{ $value }}" {!! $item['attrs'] ?? '' !!}
                                    data-op="{{ $item['op'] ?? '' }}"
                                    class="w-full bg-background border border-border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:outline-none">
                            @elseif($baseType === 'checkbox')
                                <label class="inline-flex items-center gap-2 text-sm text-foreground">
                                    <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                        data-op="{{ $item['op'] ?? '' }}"
                                        {{ request()->query($item['name']) ? 'checked' : '' }} class="accent-primary">
                                    {{ ucfirst($item['name']) }}
                                </label>
                            @elseif($baseType === 'select')
                                <select name="{{ $item['name'] }}" data-op="{{ $item['op'] ?? '' }}"
                                    class="w-full bg-background border border-border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-primary/40 focus:outline-none">
                                    <option value="">Select {{ ucfirst($item['name']) }}</option>
                                    @foreach ($item['options'] ?? [] as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ request()->query($item['name']) == $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endif
                @endfor

                {{-- Reset --}}
                <div class="pt-4 border-t border-border">
                    <a href="{{ url()->current() }}"
                        class="inline-block text-sm text-destructive hover:opacity-80 transition">
                        Reset Filters
                    </a>
                </div>

            </div>
        @endif

    </form>
</x-ui.paper>

{{-- Script --}}
@if (count($filterItems))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleFiltersBtn");
            const panel = document.getElementById("filtersPanel");

            if (toggleBtn && panel) {
                toggleBtn.addEventListener("click", function() {
                    panel.classList.toggle("hidden");
                });
            }

            const form = document.getElementById("searchFiltersForm");

            form.addEventListener("submit", function() {
                const inputs = form.querySelectorAll("[data-op]");

                inputs.forEach(input => {
                    // transform each input so that it combines with its op before submiting
                    const op = input.dataset.op;
                    if (op && input.value) {
                        input.value = op + "_" + input.value;
                    }

                    // disable empty fields to prevent sending them as filters
                    const value = input.value;
                    if (value === null || value.trim() === '') {
                        input.disabled = true;
                    }
                });

            });
        });
    </script>
@endif

{{-- 
|--------------------------------------------------------------------------
| <x-ui.search-filters />
|--------------------------------------------------------------------------
| Reusable GET-based search & filtering component with pagination-safe
| query handling.
|
| Features:
| - Main search field (required)
| - Optional filters (text, number, select, checkbox)
| - Optional operator support (e.g. gt_1000, lt_500)
| - Preserves non-filter query params
| - Removes `page` param automatically on new search/reset
| - Collapsible filter panel (no external JS required)
|
| Usage:
|
| <x-ui.search-filters
|     :items="[
|         [
|             'name' => 'search',
|             'label' => 'Search',
|             'type' => 'input:text',
|             'main' => true,
|         ],
|         [
|             'name' => 'price',
|             'label' => 'Min Price',
|             'type' => 'input:number',
|             'op' => 'gt',
|         ],
|         [
|             'name' => 'brand',
|             'label' => 'Brand',
|             'type' => 'select',
|             'options' => [
|                 'kia' => 'Kia',
|                 'toyota' => 'Toyota',
|             ],
|         ],
|     ]"
| />
|
| Notes:
| - One item must have 'main' => true.
| - 'inline' => true places the field beside the next one.
| - For 'op' fields, value is encoded as: {operator}_{value}.
| - Reset clears filters and removes page parameter.
|--------------------------------------------------------------------------
--}}

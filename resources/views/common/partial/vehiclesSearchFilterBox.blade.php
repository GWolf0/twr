@php
    $filterConfig = [
        'mainItem' => 'name',
        'items' => [
            [
                'name' => 'name',
                'type' => 'input:text',
                'op' => 'l',
                'attrs' => 'placeholder="Search vehicles (e.g. Honda, Scooter)..."',
            ],
            [
                'name' => 'type',
                'type' => 'select',
                'options' => [
                    'scooter' => 'Scooter',
                    'motorcycle' => 'Motorcycle',
                    'bicycle' => 'Bicycle',
                    'electric_bike' => 'Electric Bike',
                ],
            ],
            [
                'name' => 'price_per_hour',
                'type' => 'input:number',
                'op' => 'lt',
                'attrs' => 'placeholder="Max $/hr"',
                // 'inline' => true,
            ],
            [
                'name' => 'availability',
                'type' => 'select',
                'options' => [
                    'available' => 'Available',
                    'unavailable' => 'Unavailable',
                    'maintenance' => 'Maintenance',
                ],
            ],
            [
                'name' => 's',
                'type' => 'select',
                'options' => [
                    'price_per_hour_asc' => 'Sort: Price low to high',
                    'price_per_hour_desc' => 'Sort: Price high to low',
                ],
            ],
        ],
    ];
@endphp

<div class="w-full max-w-4xl mx-auto">
    <x-ui.search-filters :desc="$filterConfig" :action="route('common.page.search')" />
</div>

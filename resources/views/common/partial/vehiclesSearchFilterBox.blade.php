@php
    $filterConfig = [
        'mainItem' => 'q',
        'items' => [
            [
                'name' => 'q',
                'type' => 'input:text',
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
                'inline' => true,
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
        ],
    ];
@endphp

<div class="w-full max-w-4xl mx-auto">
    <x-ui.search-filters :desc="$filterConfig" :action="route('common.page.search')" />
</div>

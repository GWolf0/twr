@php
    use App\Misc\Enums\VehicleAvailability;
    use App\Misc\Enums\VehicleType;
    use function App\Helpers\enumOptions;

    // $typesOptions = enumOptions(VehicleType::class);
    $typesOptions = __('enums.vehicle_type');
    // $availabilityOptions = enumOptions(VehicleAvailability::class);
    $availabilityOptions = __('enums.vehicle_availability');

    $filterConfig = [
        'mainItem' => 'name',
        'items' => [
            [
                'name' => 'name',
                'type' => 'input:text',
                'op' => 'l',
                'attrs' => 'placeholder="' . __('copywrite.home_search_box_name_placeholder') . '"',
            ],
            [
                'name' => 'type',
                'type' => 'select',
                'options' => $typesOptions,
            ],
            [
                'name' => 'price_per_hour',
                'type' => 'input:number',
                'op' => 'lt',
                'attrs' => 'placeholder="' . __('copywrite.home_search_box_price_per_hour_placeholder') . '"',
            ],
            [
                'name' => 'availability',
                'type' => 'select',
                'options' => $availabilityOptions,
            ],
            [
                'name' => 's',
                'type' => 'select',
                'default' => 'created_at_desc',
                'options' => __('copywrite.home_search_box_sort_options'),
            ],
        ],
    ];
@endphp

<div class="w-full max-w-4xl mx-auto">
    <x-ui.search-filters :desc="$filterConfig" :action="route('common.page.search')" />
</div>

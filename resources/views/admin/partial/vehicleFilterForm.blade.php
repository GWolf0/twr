@php
    use App\Misc\Enums\VehicleAvailability;
    use function App\Helpers\enumOptions;

    $availabilityOptions = enumOptions(VehicleAvailability::class);

    $desc = [
        'mainItem' => 'name',
        'items' => [
            [
                'main' => true,
                'name' => 'name',
                'label' => 'Name',
                'type' => 'input:text',
                'op' => 'l',
                'attrs' => 'minLength=3 maxLength=64',
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'type' => 'input:text',
                'op' => 'l',
                'attrs' => 'minLength=3 maxLength=64',
            ],
            [
                'name' => 'availability',
                'label' => 'Availability',
                'type' => 'select',
                'options' => $availabilityOptions,
                'attrs' => '',
            ],
        ],
    ];
@endphp

<x-ui.search-filters :desc="$desc" />

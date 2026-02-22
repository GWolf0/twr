@php
    use App\Misc\Enums\BookingStatus;
    use App\Misc\Enums\BookingPaymentStatus;
    use App\Misc\Enums\BookingPaymentMethod;
    use function App\Helpers\enumOptions;

    $statusOptions = enumOptions(BookingStatus::class);
    $paymentStatusOptions = enumOptions(BookingPaymentStatus::class);
    $paymentMethodOptions = enumOptions(BookingPaymentMethod::class);

    $desc = [
        'mainItem' => 'id',
        'items' => [
            [
                'main' => true,
                'name' => 'id',
                'label' => 'ID',
                'type' => 'input:number',
                'op' => '=',
                'attrs' => '',
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => $statusOptions,
                'attrs' => '',
            ],
            [
                'name' => 'payment_status',
                'label' => 'Payment Status',
                'type' => 'select',
                'options' => $paymentStatusOptions,
                'attrs' => '',
            ],
            [
                'name' => 'payment_method',
                'label' => 'Payment Method',
                'type' => 'select',
                'options' => $paymentMethodOptions,
                'attrs' => '',
            ],
        ],
    ];
@endphp

<x-ui.search-filters :desc="$desc" />

@php
    use App\Models\User;
    use App\Models\Vehicle;
    use App\Misc\Enums\BookingStatus;
    use App\Misc\Enums\BookingPaymentStatus;
    use App\Misc\Enums\BookingPaymentMethod;
    use function App\Helpers\enumOptions;

    $userOptions = User::select('id', 'name')->pluck('id', 'name')->toArray();
    $vehicleOptions = Vehicle::select('id', DB::raw("CONCAT(name, ' ($', price_per_hour, '/h)') as label"))
        ->pluck('id', 'label')
        ->toArray();

    $statusOptions = enumOptions(BookingStatus::class);
    $paymentStatusOptions = enumOptions(BookingPaymentStatus::class);
    $paymentMethodOptions = enumOptions(BookingPaymentMethod::class);
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2">
            <x-ui.header h="3">Create new booking</x-ui.header>
        </div>
    </x-slot:header>

    <x-slot:content>

        <x-ui.form method="POST" action="{{ route('admin.action.store_record', ['table' => 'bookings']) }}">

            <x-ui.form-group>
                <x-ui.error key="user_id" />
                <x-ui.label for="f_user_id">User</x-ui.label>
                <x-ui.select id="f_user_id" name="user_id" :options="$userOptions" :initialValue="old('user_id')" required />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="vehicle_id" />
                <x-ui.label for="f_vehicle_id">Vehicle</x-ui.label>
                <x-ui.select id="f_vehicle_id" name="vehicle_id" :options="$vehicleOptions" :initialValue="old('vehicle_id')" required />
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.form-group>
                    <x-ui.error key="start_date" />
                    <x-ui.label for="f_start_date">Start Date</x-ui.label>
                    <x-ui.input id="f_start_date" name="start_date" type="datetime-local"
                        value="{{ old('start_date') }}" required />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="end_date" />
                    <x-ui.label for="f_end_date">End Date</x-ui.label>
                    <x-ui.input id="f_end_date" name="end_date" type="datetime-local" value="{{ old('end_date') }}"
                        required />
                </x-ui.form-group>
            </div>

            <x-ui.form-group>
                <x-ui.error key="payment_method" />
                <x-ui.label for="f_payment_method">Payment Method</x-ui.label>
                <x-ui.select id="f_payment_method" name="payment_method" :options="$paymentMethodOptions" :initialValue="old('payment_method', 'cash')" />
            </x-ui.form-group>

            {{-- 
                Note: status, payment_status, and total_amount are usually handled by the service 
                during creation, but we can include them if the admin needs to override.
                Based on BookingService::createBooking, it sets defaults.
            --}}

            <x-ui.form-actions>
                <x-ui.button type="reset" variant="outline">
                    Reset
                </x-ui.button>
                <x-ui.button type="submit">
                    Create
                </x-ui.button>
            </x-ui.form-actions>

        </x-ui.form>

    </x-slot:content>

</x-ui.card>

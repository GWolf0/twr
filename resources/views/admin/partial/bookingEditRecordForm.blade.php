@php
    use App\Models\User;
    use App\Models\Vehicle;
    use App\Misc\Enums\BookingStatus;
    use App\Misc\Enums\BookingPaymentStatus;
    use App\Misc\Enums\BookingPaymentMethod;
    use function App\Helpers\enumOptions;

    $record = $model;

    $users = User::all();
    $vehicles = Vehicle::all();

    $userOptions = $users->map(fn($u) => ['label' => $u->name, 'value' => $u->id])->toArray();
    $vehicleOptions = $vehicles->map(fn($v) => ['label' => $v->name . ' ($' . $v->price_per_hour . '/h)', 'value' => $v->id])->toArray();

    $statusOptions = enumOptions(BookingStatus::class);
    $paymentStatusOptions = enumOptions(BookingPaymentStatus::class);
    $paymentMethodOptions = enumOptions(BookingPaymentMethod::class);
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2">
            <x-ui.header h="3">Edit booking</x-ui.header>
        </div>
    </x-slot:header>

    <x-slot:content>

        <x-ui.form method="PATCH"
            action="{{ route('admin.action.update_record', ['table' => 'bookings', 'id' => $record->id]) }}">

            <x-ui.form-group>
                <x-ui.error key="user_id" />
                <x-ui.label for="f_user_id">User</x-ui.label>
                <x-ui.select id="f_user_id" name="user_id" :options="$userOptions" :initialValue="old('user_id', $record->user_id)" required />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="vehicle_id" />
                <x-ui.label for="f_vehicle_id">Vehicle</x-ui.label>
                <x-ui.select id="f_vehicle_id" name="vehicle_id" :options="$vehicleOptions" :initialValue="old('vehicle_id', $record->vehicle_id)" required />
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.form-group>
                    <x-ui.error key="start_date" />
                    <x-ui.label for="f_start_date">Start Date</x-ui.label>
                    <x-ui.input id="f_start_date" name="start_date" type="datetime-local"
                        value="{{ old('start_date', $record->start_date) }}" required />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="end_date" />
                    <x-ui.label for="f_end_date">End Date</x-ui.label>
                    <x-ui.input id="f_end_date" name="end_date" type="datetime-local"
                        value="{{ old('end_date', $record->end_date) }}" required />
                </x-ui.form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.form-group>
                    <x-ui.error key="status" />
                    <x-ui.label for="f_status">Status</x-ui.label>
                    <x-ui.select id="f_status" name="status" :options="$statusOptions" :initialValue="old('status', $record->status)" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="payment_status" />
                    <x-ui.label for="f_payment_status">Payment Status</x-ui.label>
                    <x-ui.select id="f_payment_status" name="payment_status" :options="$paymentStatusOptions" :initialValue="old('payment_status', $record->payment_status)" />
                </x-ui.form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.form-group>
                    <x-ui.error key="payment_method" />
                    <x-ui.label for="f_payment_method">Payment Method</x-ui.label>
                    <x-ui.select id="f_payment_method" name="payment_method" :options="$paymentMethodOptions" :initialValue="old('payment_method', $record->payment_method)" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="total_amount" />
                    <x-ui.label for="f_total_amount">Total Amount</x-ui.label>
                    <x-ui.input id="f_total_amount" name="total_amount" type="number" step="0.01"
                        value="{{ old('total_amount', $record->total_amount) }}" required />
                </x-ui.form-group>
            </div>

            <x-ui.form-actions>
                <x-ui.button type="reset" variant="outline">
                    Reset
                </x-ui.button>
                <x-ui.button type="submit">
                    Update
                </x-ui.button>
            </x-ui.form-actions>

        </x-ui.form>

        @if ($record->id)
            <div class="mt-8 border-t pt-4">
                <x-ui.header h="4" class="mb-4">Admin Notice</x-ui.header>
                <x-ui.text muted>
                    Note: Direct updates to bookings may be restricted by business logic. 
                    Ensure all status changes comply with the system's workflow.
                </x-ui.text>
            </div>
        @endif

    </x-slot:content>

</x-ui.card>

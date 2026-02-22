@php
    use App\Misc\Enums\VehicleAvailability;
    use function App\Helpers\enumOptions;

    $availabilityOptions = enumOptions(VehicleAvailability::class);
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2">
            <x-ui.header h="3">Create new vehicle</x-ui.header>
        </div>
    </x-slot:header>

    <x-slot:content>

        <x-ui.form method="POST" action="{{ route('admin.action.store_record', ['table' => 'vehicles']) }}">

            <x-ui.form-group>
                <x-ui.error key="name" />
                <x-ui.label for="f_name">Name</x-ui.label>
                <x-ui.input id="f_name" name="name" placeholder="name" value="{{ old('name') }}" required
                    minLength="3" maxLength="64" />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="type" />
                <x-ui.label for="f_type">Type</x-ui.label>
                <x-ui.input id="f_type" name="type" placeholder="type" value="{{ old('type') }}" required
                    minLength="3" maxLength="64" />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="price_per_hour" />
                <x-ui.label for="f_price_per_hour">Price Per Hour</x-ui.label>
                <x-ui.input id="f_price_per_hour" name="price_per_hour" type="number" step="0.01"
                    placeholder="price per hour" value="{{ old('price_per_hour') }}" required />
            </x-ui.form-group>

            @php
                $availabilityValue = old('availability', 'available');
            @endphp
            <x-ui.form-group>
                <x-ui.error key="availability" />
                <x-ui.label for="f_availability">Availability</x-ui.label>
                <x-ui.select id="f_availability" name="availability" :options="$availabilityOptions" :initialValue="$availabilityValue" />
            </x-ui.form-group>

            <x-forms.vehicle-images-field :media="old('media', '')" />

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

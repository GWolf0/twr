@extends('layouts.mainLayout')

@section('content')
    <x-ui.paper class="max-w-3xl mx-auto">

        <x-ui.card>
            <x-slot name="header">
                <h2 class="text-xl font-semibold">
                    Book Vehicle — {{ $vehicle->name }}
                </h2>
            </x-slot>

            <x-slot name="content">

                {{-- Step Indicator --}}
                <div class="flex items-center justify-between mb-6 text-sm font-medium">
                    <div id="step-indicator-1" class="text-primary">1. Details</div>
                    <div class="text-muted-foreground">—</div>
                    <div id="step-indicator-2" class="text-muted-foreground">2. Summary</div>
                    <div class="text-muted-foreground">—</div>
                    <div id="step-indicator-3" class="text-muted-foreground">3. Confirmation</div>
                </div>

                {{-- Alerts --}}
                <div id="availability-alert" class="mb-4 hidden"></div>
                <div id="general-alert" class="mb-4 hidden"></div>

                {{-- STEP 1 --}}
                <div id="step-1">

                    <x-ui.form-group>
                        <x-ui.label>Start Date</x-ui.label>
                        <x-ui.input type="datetime-local" id="start_date" />
                    </x-ui.form-group>

                    <x-ui.form-group>
                        <x-ui.label>End Date</x-ui.label>
                        <x-ui.input type="datetime-local" id="end_date" />
                    </x-ui.form-group>

                    <x-ui.form-group>
                        <x-ui.label>Payment Method</x-ui.label>
                        <select id="payment_method" class="w-full border border-border rounded-md p-2 bg-input">
                            <option value="cash">Cash (Pay at agency)</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </x-ui.form-group>

                    <div class="mt-6 text-right">
                        <x-ui.button variant="primary" id="to-step-2">
                            Continue
                        </x-ui.button>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div id="step-2" class="hidden">

                    <div id="summary-box" class="space-y-2 text-sm"></div>

                    <div class="mt-6 flex justify-between">
                        <x-ui.button variant="ghost" id="back-to-step-1">
                            Back
                        </x-ui.button>

                        <x-ui.button variant="primary" id="confirm-booking">
                            Confirm Booking
                        </x-ui.button>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div id="step-3" class="hidden text-center space-y-4">
                    <x-ui.alert message="Booking created successfully!" severity="info" />
                    <a href="{{ route('customer.page.bookings') }}">
                        <x-ui.button variant="primary">
                            Go to My Bookings
                        </x-ui.button>
                    </a>
                </div>

            </x-slot>
        </x-ui.card>

    </x-ui.paper>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const vehicleId = {{ $vehicle->id }};

            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');

            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            const paymentInput = document.getElementById('payment_method');

            const availabilityAlert = document.getElementById('availability-alert');
            const generalAlert = document.getElementById('general-alert');
            const summaryBox = document.getElementById('summary-box');

            let availabilityValid = false;
            let calculatedAmount = 0;
            let calculatedHours = 0;

            function showAlert(container, message, severity = 'info') {
                container.innerHTML = `<x-ui.alert message="${message}" severity="${severity}" />`;
                container.classList.remove('hidden');
            }

            function hideAlert(container) {
                container.innerHTML = '';
                container.classList.add('hidden');
            }

            async function checkAvailability() {
                if (!startInput.value || !endInput.value) return;

                const response = await fetch('/api/bookings/can-book', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        start_date: startInput.value,
                        end_date: endInput.value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    availabilityValid = true;
                    showAlert(availabilityAlert, "Vehicle available ✔", "info");
                } else {
                    availabilityValid = false;
                    showAlert(availabilityAlert, data.message || "Not available", "error");
                }
            }

            startInput.addEventListener('change', checkAvailability);
            endInput.addEventListener('change', checkAvailability);

            document.getElementById('to-step-2').addEventListener('click', async function() {

                if (!availabilityValid) {
                    showAlert(generalAlert, "Please select valid available dates.", "error");
                    return;
                }

                const response = await fetch('/api/bookings/calculate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        start_date: startInput.value,
                        end_date: endInput.value
                    })
                });

                const data = await response.json();

                calculatedAmount = data.amount;
                calculatedHours = data.hours;

                summaryBox.innerHTML = `
            <div><strong>Vehicle:</strong> {{ $vehicle->name }}</div>
            <div><strong>From:</strong> ${startInput.value}</div>
            <div><strong>To:</strong> ${endInput.value}</div>
            <div><strong>Duration:</strong> ${calculatedHours} hours</div>
            <div><strong>Total:</strong> $${calculatedAmount}</div>
            <div><strong>Payment:</strong> ${paymentInput.value}</div>
        `;

                step1.classList.add('hidden');
                step2.classList.remove('hidden');
            });

            document.getElementById('back-to-step-1')
                .addEventListener('click', function() {
                    step2.classList.add('hidden');
                    step1.classList.remove('hidden');
                });

            document.getElementById('confirm-booking')
                .addEventListener('click', async function() {

                    const response = await fetch('/api/bookings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            vehicle_id: vehicleId,
                            start_date: startInput.value,
                            end_date: endInput.value,
                            payment_method: paymentInput.value
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        showAlert(generalAlert, data.message || "Error creating booking", "error");
                        return;
                    }

                    // CONDITIONAL PAYMENT FLOW
                    if (paymentInput.value === 'credit_card') {
                        // Here later you redirect to Stripe
                        window.location.href = `/payment/start/${data.booking.id}`;
                        return;
                    }

                    // Cash flow
                    step2.classList.add('hidden');
                    step3.classList.remove('hidden');
                });

        });
    </script>
@endsection

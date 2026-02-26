@extends('layouts.mainLayout')

@section('content')
    <x-ui.card>
        <x-slot name="header">
            <h2 class="text-xl font-semibold">
                Book Vehicle | {{ $vehicle->name }}
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
            <section id="step-1">
                <div class="flex flex-col gap-2">
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
                        @php
                            $paymentMethodsOptions = [
                                'Cash (Pay at agency)' => 'cash',
                                'Credit Card' => 'credit_card',
                            ];
                        @endphp
                        <x-ui.select id="payment_method" :options="$paymentMethodsOptions" />
                    </x-ui.form-group>
                </div>

                <div class="mt-6 text-right">
                    <x-ui.button variant="primary" id="to-step-2">
                        Continue
                    </x-ui.button>
                </div>
            </section>

            {{-- STEP 2 --}}
            <section id="step-2" class="hidden">

                <div id="summary-box" class="space-y-2 text-sm"></div>

                <div class="mt-6 flex justify-between">
                    <x-ui.button variant="outline" id="back-to-step-1">
                        Back
                    </x-ui.button>

                    <x-ui.button variant="primary" id="confirm-booking">
                        Confirm Booking
                    </x-ui.button>
                </div>
            </section>

            {{-- STEP 3 --}}
            <section id="step-3" class="hidden text-center space-y-4">
                <x-ui.alert message="Booking created successfully!" severity="info" />
                <a href="{{ route('customer.page.bookings_list') }}">
                    <x-ui.button variant="primary">
                        Go to My Bookings
                    </x-ui.button>
                </a>
            </section>

        </x-slot>
    </x-ui.card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const vehicleId = {{ $vehicle->id }};
            const userId = {{ auth()->id() }};

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
                console.log("checkAvailability", startInput.value, endInput.value);
                if (!startInput.value || !endInput.value) return;

                const response = await fetch('/api/v1/booking/can-book', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "ACCEPT": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content"),
                        "AUTHORIZATION": "Bearer " + document.querySelector('meta[name="api-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        user_id: userId,
                        start_date: startInput.value,
                        end_date: endInput.value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    availabilityValid = true;
                    showAlert(availabilityAlert, "Vehicle available", "info");
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

                const response = await fetch('/api/v1/booking/calculate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "ACCEPT": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute(
                                "content"),
                        "AUTHORIZATION": "Bearer " + document.querySelector(
                                'meta[name="api-token"]')
                            .getAttribute("content"),
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
                    document.getElementById('confirm-booking').disabled = true;
                    document.getElementById('confirm-booking').innerHTML = "Booking..";

                    const response = await fetch('/api/v1/booking', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "ACCEPT": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .getAttribute(
                                    "content"),
                            "AUTHORIZATION": "Bearer " + document.querySelector(
                                    'meta[name="api-token"]')
                                .getAttribute("content"),
                        },
                        body: JSON.stringify({
                            vehicle_id: vehicleId,
                            user_id: userId,
                            start_date: startInput.value,
                            end_date: endInput.value,
                            payment_method: paymentInput.value
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        console.log(data);
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
@endpush

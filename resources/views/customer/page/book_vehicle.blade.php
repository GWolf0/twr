@extends('layouts.mainLayout')

@php
    $firstImage = explode(',', $vehicle->media)[0];
@endphp

@section('content')
    <x-ui.card>
        <x-slot name="header">
            <div class="flex items-center gap-4">
                {{-- Vehicle image --}}
                <img src="{{ $firstImage }}" class="w-16 h-16 rounded object-cover">

                <div>
                    <h2 class="text-xl font-semibold">
                        Book Vehicle | {{ $vehicle->name }}
                    </h2>
                    <p class="text-sm text-muted-foreground">
                        ${{ $vehicle->price_per_hour }}/hour
                    </p>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">

            {{-- STEP PROGRESS INDICATOR --}}
            <div class="flex items-center justify-between mb-8">

                <div class="flex items-center gap-2">
                    <div id="circle-1" class="step-circle active">1</div>
                    <span id="label-1" class="step-label active">Details</span>
                </div>

                <div class="flex-1 h-px bg-muted mx-4"></div>

                <div class="flex items-center gap-2">
                    <div id="circle-2" class="step-circle">2</div>
                    <span id="label-2" class="step-label">Summary</span>
                </div>

                <div class="flex-1 h-px bg-muted mx-4"></div>

                <div class="flex items-center gap-2">
                    <div id="circle-3" class="step-circle">3</div>
                    <span id="label-3" class="step-label">Confirmation</span>
                </div>

            </div>

            {{-- Alerts --}}
            <div id="availability-alert" class="mb-4 hidden"></div>
            <div id="general-alert" class="mb-4 hidden"></div>

            {{-- STEP 1 : DETAILS --}}
            <section id="step-1" class="transition-opacity duration-200">

                <div class="flex flex-col gap-4">

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
                    <x-ui.button variant="primary" id="to-step-2" disabled>
                        Continue
                    </x-ui.button>
                </div>

            </section>

            {{-- STEP 2 : SUMMARY --}}
            <section id="step-2" class="hidden transition-opacity duration-200">

                <div id="summary-box"></div>

                <div class="mt-6 flex justify-between">
                    <x-ui.button variant="outline" id="back-to-step-1">
                        Back
                    </x-ui.button>

                    <x-ui.button variant="primary" id="confirm-booking">
                        Confirm Booking
                    </x-ui.button>
                </div>

            </section>

            {{-- ============================= --}}
            {{-- STEP 3 : SUCCESS --}}
            {{-- ============================= --}}
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
            // BASIC VARIABLES

            const vehicleId = {{ $vehicle->id }};
            const userId = {{ auth()->id() }};

            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');

            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            const paymentInput = document.getElementById('payment_method');

            const continueBtn = document.getElementById('to-step-2');
            const confirmBtn = document.getElementById('confirm-booking');

            const availabilityAlert = document.getElementById('availability-alert');
            const generalAlert = document.getElementById('general-alert');
            const summaryBox = document.getElementById('summary-box');

            let availabilityValid = false;
            let calculatedAmount = 0;
            let calculatedHours = 0;
            let debounceTimeout;

            const paymentLabels = {
                cash: "Cash (Pay at agency)",
                credit_card: "Credit Card"
            };

            // ALERT HELPERS

            function showAlert(container, message, severity = 'info') {
                console.log(`Show Alert: ${message}`);
                container.innerHTML =
                    `<p class="text-sm ${severity=='error'?'text-destructive':'text-primary'}">${message}</p>`;
                container.classList.remove('hidden');
            }

            function hideAlert(container) {
                container.innerHTML = '';
                container.classList.add('hidden');
            }

            // =============================
            // STEPPER STATE UPDATE
            // =============================

            function setStep(stepNumber) {

                [1, 2, 3].forEach(i => {
                    document.getElementById('circle-' + i).classList.remove('active');
                    document.getElementById('label-' + i).classList.remove('active');
                });

                if (stepNumber > 1)
                    document.getElementById('circle-1').classList.add('completed');

                if (stepNumber > 2)
                    document.getElementById('circle-2').classList.add('completed');

                document.getElementById('circle-' + stepNumber).classList.add('active');
                document.getElementById('label-' + stepNumber).classList.add('active');
            }

            // =============================
            // VALIDATION
            // =============================

            function validateStep1() {

                const start = new Date(startInput.value);
                const end = new Date(endInput.value);

                const basicValid =
                    startInput.value &&
                    endInput.value &&
                    end > start;

                continueBtn.disabled = !(basicValid && availabilityValid);
            }

            // =============================
            // DEBOUNCED AVAILABILITY CHECK
            // =============================

            function debounceCheckAvailability() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(checkAvailability, 500);
            }

            async function checkAvailability() {

                if (!startInput.value || !endInput.value) return;

                const start = new Date(startInput.value);
                const end = new Date(endInput.value);

                if (end <= start) {
                    availabilityValid = false;
                    showAlert(availabilityAlert, "End date must be after start date.", "error");
                    validateStep1();
                    return;
                }

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

                validateStep1();
            }

            startInput.addEventListener('change', debounceCheckAvailability);
            endInput.addEventListener('change', debounceCheckAvailability);

            // =============================
            // STEP TRANSITIONS
            // =============================

            continueBtn.addEventListener('click', async function() {

                if (continueBtn.disabled) return;

                const response = await fetch('/api/v1/booking/calculate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "ACCEPT": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "AUTHORIZATION": "Bearer " + document.querySelector(
                            'meta[name="api-token"]').getAttribute("content"),
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
        <div class="bg-muted/30 rounded-lg p-6 space-y-4 border border-border">

            <div class="flex justify-between">
                <span class="text-muted-foreground">Vehicle</span>
                <span class="font-medium">{{ $vehicle->name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-muted-foreground">Rental Period</span>
                <span class="font-medium">
                    ${new Date(startInput.value).toLocaleString()} →
                    ${new Date(endInput.value).toLocaleString()}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-muted-foreground">Duration</span>
                <span class="font-medium">${calculatedHours} hours</span>
            </div>

            <div class="flex justify-between">
                <span class="text-muted-foreground">Payment</span>
                <span class="font-medium">${paymentLabels[paymentInput.value]}</span>
            </div>

            <div class="border-t border-border pt-4 flex justify-between text-lg font-semibold">
                <span>Total</span>
                <span>$${calculatedAmount}</span>
            </div>

        </div>
        `;

                step1.classList.add('hidden');
                step2.classList.remove('hidden');

                setStep(2);
            });

            document.getElementById('back-to-step-1').addEventListener('click', function() {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                setStep(1);
            });

            confirmBtn.addEventListener('click', async function() {

                confirmBtn.disabled = true;
                confirmBtn.innerHTML = "Processing...";

                const response = await fetch('/api/v1/booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "ACCEPT": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "AUTHORIZATION": "Bearer " + document.querySelector(
                            'meta[name="api-token"]').getAttribute("content"),
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
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = "Confirm Booking";
                    showAlert(generalAlert, data.message || "Error creating booking", "error");
                    return;
                }

                if (paymentInput.value === 'credit_card') {
                    window.location.href = `/payment/start/${data.booking.id}`;
                    return;
                }

                step2.classList.add('hidden');
                step3.classList.remove('hidden');
                setStep(3);
            });

        });
    </script>
@endpush

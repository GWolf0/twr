@extends('layouts.mainLayout')

@section('title', 'Welcome to Two Wheeler Rentals')

@section('content')

    <div class="space-y-16 py-8">
        {{-- Hero Section --}}
        <section class="text-center space-y-8 py-12">
            <div class="space-y-4">
                <p class="text-5xl font-medium text-center mb-10 text-shadow-lg">{{ config('app.name') }}</p>
                <x-ui.header h="1">Rent Your Dream Two-Wheeler Today</x-ui.header>
                <x-ui.text size="lg" muted class="max-w-2xl mx-auto">
                    From agile scooters to powerful motorcycles and eco-friendly bicycles. Find the perfect ride for your
                    next adventure.
                </x-ui.text>
            </div>

            <div class="pt-4">
                @include('common.partial.vehiclesSearchFilterBox')
            </div>
        </section>

        {{-- How It Works --}}
        <section class="space-y-12">
            <div class="text-center">
                <x-ui.header h="2">How It Works</x-ui.header>
                <x-ui.text muted>Three simple steps to get you on the road.</x-ui.text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-search text-3xl"></i>
                    </div>
                    <x-ui.header h="3">1. Search</x-ui.header>
                    <x-ui.text size="sm" muted>Browse our wide selection of vehicles and find the one that fits your
                        needs.</x-ui.text>
                </x-ui.paper>

                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-calendar-check text-3xl"></i>
                    </div>
                    <x-ui.header h="3">2. Book</x-ui.header>
                    <x-ui.text size="sm" muted>Choose your dates, provide your details, and confirm your booking
                        instantly.</x-ui.text>
                </x-ui.paper>

                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-bicycle text-3xl"></i>
                    </div>
                    <x-ui.header h="3">3. Ride</x-ui.header>
                    <x-ui.text size="sm" muted>Pick up your vehicle at our location and enjoy your ride!</x-ui.text>
                </x-ui.paper>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section class="space-y-12 bg-muted/30 -mx-4 px-4 py-20 rounded-[3rem]">
            <div class="text-center">
                <x-ui.header h="2">Frequently Asked Questions</x-ui.header>
                <x-ui.text muted>Everything you need to know about renting with us.</x-ui.text>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                @php
                    $faqs = [
                        [
                            'q' => 'What do I need to rent a vehicle?',
                            'a' =>
                                'You need a valid ID, a driver\'s license (for motorcycles/scooters), and a credit card for the security deposit.',
                        ],
                        [
                            'q' => 'Is insurance included?',
                            'a' =>
                                'Basic insurance is included in all rentals. You can also opt for premium coverage during booking.',
                        ],
                        [
                            'q' => 'Can I cancel my booking?',
                            'a' => 'Yes, you can cancel up to 24 hours before your rental starts for a full refund.',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $faq)
                    <x-ui.card>
                        <x-slot name="header">
                            <x-ui.header h="3" class="text-lg">{{ $faq['q'] }}</x-ui.header>
                        </x-slot>
                        <x-slot name="content">
                            <x-ui.text size="sm" muted>{{ $faq['a'] }}</x-ui.text>
                        </x-slot>
                    </x-ui.card>
                @endforeach
            </div>
        </section>
    </div>

@endsection

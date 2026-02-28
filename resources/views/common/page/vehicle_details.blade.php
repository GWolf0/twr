@extends('layouts.mainLayout')

@section('title', $model->name)

@section('content')

    <div class="space-y-8">
        {{-- Breadcrumbs / Back button --}}
        <div class="flex items-center gap-2">
            <x-ui.button variant="ghost" size="sm" :href="route('common.page.search')">
                <i class="bi bi-arrow-left mr-2"></i>
                {{ __('copywrite.vehicles_details_back_to_search') }}
            </x-ui.button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Left: Carousel --}}
            <div class="space-y-4">
                <x-ui.carousel :images="$model->media" />
            </div>

            {{-- Right: Details --}}
            <div class="space-y-8">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="space-y-1">
                            <x-ui.badge variant="secondary" size="sm">{{ ucfirst($model->type) }}</x-ui.badge>
                            <x-ui.header h="1">{{ $model->name }}</x-ui.header>
                        </div>

                        @php
                            $badgeVariant = match ($model->availability) {
                                \App\Misc\Enums\VehicleAvailability::available->name => 'success',
                                \App\Misc\Enums\VehicleAvailability::unavailable->name => 'destructive',
                                \App\Misc\Enums\VehicleAvailability::maintenance->name => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <div class="flex items-start">
                            <x-ui.badge :variant="$badgeVariant" size="lg" class="px-4 py-2">
                                {{ ucfirst(__('enums.vehicle_availability.' . $model->availability)) }}
                            </x-ui.badge>
                        </div>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <x-ui.text size="lg" class="text-3xl font-bold text-primary">
                            ${{ number_format($model->price_per_hour, 2) }}
                        </x-ui.text>
                        <x-ui.text muted>/ {{ __('common.hour') }}</x-ui.text>
                    </div>
                </div>

                <x-ui.separator />

                <div class="space-y-4">
                    <x-ui.header h="3">{{ __('copywrite.vehicles_details_about_this_vehicle') }}</x-ui.header>
                    <x-ui.text muted class="leading-relaxed">
                        Experience the road like never before with the {{ $model->name }}. Perfect for city commuting and
                        weekend escapes.
                        Our vehicles are rigorously maintained to ensure the highest safety standards and a smooth riding
                        experience.
                        Whether you're a local or just visiting, this {{ $model->type }} is the perfect companion for your
                        travels.
                    </x-ui.text>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-ui.paper class="flex items-center gap-4 border border-border/50 bg-muted/5">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <i class="bi bi-speedometer2 text-xl"></i>
                        </div>
                        <div>
                            <x-ui.text size="sm" muted>Condition</x-ui.text>
                            <x-ui.text size="sm" class="font-medium">Excellent</x-ui.text>
                        </div>
                    </x-ui.paper>
                    <x-ui.paper class="flex items-center gap-4 border border-border/50 bg-muted/5">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                            <i class="bi bi-shield-check text-xl"></i>
                        </div>
                        <div>
                            <x-ui.text size="sm" muted>Insurance</x-ui.text>
                            <x-ui.text size="sm" class="font-medium">Basic Included</x-ui.text>
                        </div>
                    </x-ui.paper>
                </div>

                <div class="pt-6">
                    @if ($model->availability === \App\Misc\Enums\VehicleAvailability::available->name)
                        <x-ui.button variant="primary" size="lg"
                            class="w-full text-lg h-14 shadow-lg shadow-primary/20" :href="route('customer.page.book_vehicle', ['vehicle_id' => $model->id])" :disabled="!auth()->check()">
                            {{ __('copywrite.vehicles_details_book_this_vehicle') }}
                        </x-ui.button>
                    @else
                        <x-ui.button variant="secondary" size="lg" class="w-full text-lg h-14" disabled>
                            {{ __('copywrite.vehicles_details_currently_unavailable') }}
                        </x-ui.button>
                    @endif
                    <x-ui.text size="sm" muted class="text-center mt-4">
                        <i class="bi bi-info-circle mr-1"></i>
                        @auth
                            {{ __('copywrite.vehicles_details_booking_page_redirect_notice') }}
                        @endauth
                        @guest
                            {{ __('common.please') }}
                            <x-ui.link href="{{ route('auth.page.login') }}" target="_blank ">
                                {{ __('common.login/register') }}
                            </x-ui.link>
                            {{ __('copywrite.vehicles_details_auth_notice') }}
                        @endguest
                    </x-ui.text>
                </div>
            </div>
        </div>
    </div>

@endsection

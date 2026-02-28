@extends('layouts.mainLayout')

@section('title', 'Welcome to Two Wheeler Rentals')

@section('content')

    <div class="space-y-16 py-8">
        {{-- Hero Section --}}
        <section class="text-center space-y-8 py-12">
            <div class="space-y-4">
                <p class="text-5xl font-medium text-center mb-10 text-shadow-lg">{{ config('app.name') }}</p>
                <x-ui.header h="1">{{ __('copywrite.home_hero_title') }}</x-ui.header>
                <x-ui.text size="lg" muted class="max-w-2xl mx-auto">
                    {{ __('copywrite.home_hero_sub') }}
                </x-ui.text>
            </div>

            <div class="pt-4">
                @include('common.partial.vehiclesSearchFilterBox')
            </div>
        </section>

        {{-- How It Works --}}
        <section class="space-y-12">
            <div class="text-center">
                <x-ui.header h="2">{{ __('copywrite.home_how_it_works') }}</x-ui.header>
                <x-ui.text muted>{{ __('copywrite.home_how_it_works_sub') }}</x-ui.text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-search text-3xl"></i>
                    </div>
                    <x-ui.header h="3">{{ __('copywrite.home_how_it_works_step_1') }}</x-ui.header>
                    <x-ui.text size="sm" muted>{{ __('copywrite.home_how_it_works_step_1_sub') }}</x-ui.text>
                </x-ui.paper>

                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-calendar-check text-3xl"></i>
                    </div>
                    <x-ui.header h="3">{{ __('copywrite.home_how_it_works_step_2') }}</x-ui.header>
                    <x-ui.text size="sm" muted>{{ __('copywrite.home_how_it_works_step_2_sub') }}</x-ui.text>
                </x-ui.paper>

                <x-ui.paper padding="lg" class="text-center space-y-4 border border-border/50">
                    <div
                        class="mx-auto w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-bicycle text-3xl"></i>
                    </div>
                    <x-ui.header h="3">{{ __('copywrite.home_how_it_works_step_3') }}</x-ui.header>
                    <x-ui.text size="sm" muted>{{ __('copywrite.home_how_it_works_step_3_sub') }}</x-ui.text>
                </x-ui.paper>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section class="space-y-12 bg-muted/30 -mx-4 px-4 py-20 rounded-[3rem]">
            <div class="text-center">
                <x-ui.header h="2">{{ __('copywrite.home_faq') }}</x-ui.header>
                <x-ui.text muted>{{ __('copywrite.home_faq_sub') }}</x-ui.text>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                @php
                    $faqs = __('copywrite.home_faq_array');
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

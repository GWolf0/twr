@extends('layouts.mainLayout')

@section('title', 'Search Vehicles')

@section('content')

    <div class="space-y-8">
        <div class="flex flex-col justify-between gap-6">
            <div class="w-full">
                @include('common.partial.vehiclesSearchFilterBox')
            </div>

            <div class="space-y-1">
                <x-ui.header h="1">Find Your Ride</x-ui.header>
                <x-ui.text muted>Showing {{ $models->total() }} vehicles found</x-ui.text>
            </div>
        </div>

        <div class="border-t border-border pt-8">
            @if ($models->isEmpty())
                <div class="py-20 text-center space-y-6 bg-muted/20 rounded-3xl border-2 border-dashed border-border">
                    <div
                        class="mx-auto w-16 h-16 bg-muted text-muted-foreground rounded-full flex items-center justify-center">
                        <i class="bi bi-search text-3xl"></i>
                    </div>
                    <div class="space-y-2">
                        <x-ui.header h="3">No vehicles found</x-ui.header>
                        <x-ui.text muted>Try adjusting your filters or search terms.</x-ui.text>
                    </div>
                    <x-ui.button variant="outline" :href="route('common.page.search')">
                        Clear All Filters
                    </x-ui.button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($models as $vehicle)
                        <x-vehicle.card :vehicle="$vehicle" />
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    <x-ui.pagination :currentPage="$models->currentPage()" :lastPage="$models->lastPage()" :query="request()->query()" />
                </div>
            @endif
        </div>
    </div>

@endsection

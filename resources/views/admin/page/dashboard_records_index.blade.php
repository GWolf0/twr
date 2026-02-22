<!--
dashboard records index page
- extends layout "layouts/adminLayout"
- displays a filtering form and a table list of the requested model
- the filtering form and records table can be found in "/resources/views/admin/partial"
- check from the "AdminController" in the method "indexRecords"
- the requested model can be found in the var "$table" so we can render the appropriate filering form and the records table
-->
@extends('layouts.adminLayout')

@php
    $table = request()->route('table');
@endphp

@section('content')
    @php
        $filterPartial = match ($table) {
            'users' => 'admin.partial.usersFilterForm',
            'vehicles' => 'admin.partial.vehicleFilterForm',
            'bookings' => 'admin.partial.bookingsFilterForm',
            default => null,
        };

        $tablePartial = match ($table) {
            'users' => 'admin.partial.usersRecordsTable',
            'vehicles' => 'admin.partial.vehiclesRecordsTable',
            'bookings' => 'admin.partial.bookingsRecordsTable',
            default => null,
        };
    @endphp

    @if ($filterPartial)
        @include($filterPartial)
    @endif

    <div class="my-4"></div>

    @if ($tablePartial)
        @include($tablePartial)
    @else
        <x-ui.card>
            <x-slot:content>
                <x-ui.text class="text-destructive">Unknown table: {{ $table }}</x-ui.text>
            </x-slot:content>
        </x-ui.card>
    @endif
@endsection()

<!--
dashboard record create page
- extends layout "layouts/adminLayout"
- displays a form to create a new record
- the form can be found in "/resources/views/admin/partial"
- check from the "AdminController" in the method "createRecord"
- the requested model can be found in the var "$table" so we can render the appropriate form from the partial
-->

@extends('layouts.adminLayout')

@php
    $table = request()->route('table');
@endphp

@section('content')
    @php
        $formPartial = match ($table) {
            'users' => 'admin.partial.userNewRecordForm',
            'vehicles' => 'admin.partial.vehicleNewRecordForm',
            'bookings' => 'admin.partial.bookingNewRecordForm',
            default => null,
        };
    @endphp

    @if ($formPartial)
        @include($formPartial)
    @else
        <x-ui.card>
            <x-slot:content>
                <x-ui.text class="text-destructive">Unknown table: {{ $table }}</x-ui.text>
            </x-slot:content>
        </x-ui.card>
    @endif
@endsection()
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
    @switch($table)
        @case('users')
            @include('admin.partial.usersRecordsTable')
        @break

        @case(2)
            @include('admin.partial.usersRecordsTable')
        @break

        @default
            @include('admin.partial.usersRecordsTable')
    @endswitch
@endsection()

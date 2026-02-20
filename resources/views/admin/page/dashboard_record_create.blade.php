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
    @switch($table)
        @case('users')
            @include('admin.partial.userNewRecordForm')
        @break

        @case(2)
            @include('admin.partial.userNewRecordForm')
        @break

        @default
            @include('admin.partial.userNewRecordForm')
    @endswitch
@endsection()
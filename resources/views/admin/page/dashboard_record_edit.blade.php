<!--
dashboard record edit page
- extends layout "layouts/adminLayout"
- displays a form to edit a record
- the form can be found in "/resources/views/admin/partial"
- check from the "AdminController" in the method "editRecord"
- the requested model can be found in the var "$table" so we can render the appropriate form from the partial
- the id of the model to edit can be found in the var "$id"
-->

@extends('layouts.adminLayout')

@php
    $table = request()->route('table');
@endphp

@section('content')
    @switch($table)
        @case('users')
            @include('admin.partial.userEditRecordForm')
        @break

        @case(2)
            @include('admin.partial.userEditRecordForm')
        @break

        @default
            @include('admin.partial.userEditRecordForm')
    @endswitch
@endsection()

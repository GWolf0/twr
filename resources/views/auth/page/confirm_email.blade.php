@extends('layouts.mainLayout')

@php
    $alertSeverity = $status < 400 ? 'info' : 'error';
@endphp

@section('content')
    <main class="w-full grow flex flex-col gap-8 items-center justify-center">
        <x-ui.alert :message="$message" :severity="$alertSeverity" />
    </main>
@endsection

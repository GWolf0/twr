@extends('layouts.mainLayout')
@php
    // dd(session('data'));
@endphp
@section('content')
    <main class="w-full grow flex items-center justify-center">
        @include('auth.partial.registerBox')
    </main>

    {{-- {{ json_encode($merrors) }} --}}
@endsection

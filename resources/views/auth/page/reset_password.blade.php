@extends('layouts.mainLayout')

@section('content')
    <main class="w-full grow flex items-center justify-center">
        @include('auth.partial.passwordResetBox')
    </main>
@endsection
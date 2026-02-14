@extends('layouts.mainLayout')

@section('content')
    <main class="w-full grow flex flex-col gap-8 items-center justify-center">
        <x-ui.alert
            message="Your email has been successfully confirmed" 
        />
    </main>
@endsection
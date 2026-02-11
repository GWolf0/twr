@extends('layouts.mainLayout')

@section('content')
    
    <section class="w-full mt-30">
        
        {{-- // register box --}}
        <main class="rounded bg-gray-100 shadow px-4 py-8 mx-auto" style="width: min(100%, 480px)">

            <x-forms.auth.registerForm />

        </main>

    </section>

@endsection
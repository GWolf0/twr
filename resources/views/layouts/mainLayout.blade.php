<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Page')</title>
    {{-- // bootstrap-icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="w-full bg-background @yield('outerClasses')">
    
    {{-- // header --}}
    <x-layout.mainHeader />

    {{-- // content --}}
    <div class="min-h-screen mx-auto px-2 md:px-4 py-8 @yield('innerClasses')" style="width: min(100%, 1280px)">
        @yield('content')
    </div>

    {{-- // footer --}}
    <x-layout.mainFooter />

    @stack('scripts')
</body>
</html>
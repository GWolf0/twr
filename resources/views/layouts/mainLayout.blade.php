<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="api-token" content="{{ session('api_token', '') }}" />

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Page')</title>
    {{-- // bootstrap-icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="w-full bg-background @yield('outerClasses')">
    {{-- // background image --}}
    @if (request()->routeIs('common.page.home', 'auth.page.login', 'auth.page.register'))
        <div class="absolute w-full z-[-1] opacity-30"
            style="height: 100vh; top: 0px; background-image: url(banner_raw.png); background-repeat: no-repeat; background-position: center; background-size: cover;">

        </div>
    @endif

    {{-- // header --}}
    <x-layout.main-header />

    {{-- // content --}}
    <div class="min-h-screen mx-auto px-2 md:px-4 py-8 @yield('innerClasses')" style="width: min(100%, 1280px)">
        {{-- // alerts container --}}
        <x-ui.email-confirmation-alert />
        <x-ui.alerts-container />

        @yield('content')
    </div>

    {{-- // footer --}}
    <x-layout.main-footer />

    {{-- // toasts container --}}
    <x-ui.toasts-container />

    {{-- // modals container --}}
    <x-ui.modals-container>

        @stack('modals')
    </x-ui.modals-container>

    @stack('scripts')
</body>

</html>

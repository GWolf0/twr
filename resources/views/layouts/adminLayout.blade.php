<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="api-token" content="{{ session('api_token', '') }}" />
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Admin') </title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-background text-foreground min-h-screen">

    <div class="flex min-h-screen">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="w-64 bg-card border-r border-border hidden md:flex flex-col">

            {{-- Logo / Title --}}
            <div class="h-16 flex items-center gap-3 px-6 border-b border-border">
                <x-layout.logo size="sm" />
                <span class="text-xl font-semibold text-primary">
                    {{ config('app.name') }} Admin
                </span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

                <a href="{{ route('admin.page.dashboard_stats') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-secondary hover:text-foreground">
                    <i class="bi bi-speedometer2"></i>
                    <span>Stats</span>
                </a>

                <a href="{{ route('admin.page.dashboard_settings') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-secondary hover:text-foreground">
                    <i class="bi bi-gear-wide"></i>
                    <span>Setings</span>
                </a>

                <a href="{{ route('admin.page.dashboard_records_index', ['table' => 'users']) }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-secondary hover:text-foreground">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.page.dashboard_records_index', ['table' => 'vehicles']) }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-secondary hover:text-foreground">
                    <i class="bi bi-circle"></i>
                    <span>Vehicles</span>
                </a>

                <a href="{{ route('admin.page.dashboard_records_index', ['table' => 'bookings']) }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-secondary hover:text-foreground">
                    <i class="bi bi-ticket-detailed"></i>
                    <span>Bookings</span>
                </a>

            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-border text-xs text-muted-foreground">
                © {{ now()->year }} {{ config('app.name') }}
            </div>

        </aside>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="flex-1 flex flex-col">

            {{-- ================= TOPBAR ================= --}}
            <header class="h-16 bg-card border-b border-border flex items-center justify-between px-6">

                <div class="flex items-center gap-3">

                    {{-- Mobile menu toggle placeholder (for later JS) --}}
                    <button class="md:hidden text-foreground">
                        <i class="bi bi-list text-xl"></i>
                    </button>

                    <h1 class="text-base font-semibold">
                        @yield('title', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-4 text-sm">

                    {{-- User info --}}
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-circle text-lg text-primary"></i>
                        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('auth.action.logout') }}">
                        @csrf
                        <button class="px-3 py-1.5 rounded-lg bg-secondary hover:bg-muted transition text-foreground">
                            Logout
                        </button>
                    </form>

                </div>
            </header>

            {{-- ================= PAGE CONTENT ================= --}}
            <main class="flex-1 p-6">

                {{-- Alerts --}}
                <x-ui.alerts-container />

                {{-- Page Content --}}
                @yield('content')

            </main>

        </div>

    </div>

    {{-- Toasts --}}
    <x-ui.toasts-container />

    {{-- Modals --}}
    <x-ui.modals-container>
        <x-ui.file-upload-manager />

        @stack('modals')
    </x-ui.modals-container>

    @stack('scripts')

</body>

</html>

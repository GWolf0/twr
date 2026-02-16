<!--
dashboard stats page
- extends layout "layouts/adminLayout"
- default admin page
- displays the stats
- the stats data can be check from the "AdminController" in the method "stats"
-->

@extends('layouts.adminLayout')

@section('title', 'Dashboard')

@section('content')

    <div class="space-y-8">

        {{-- ================= PAGE HEADER ================= --}}
        <div>
            <x-ui.header h="2">Dashboard Overview</x-ui.header>
            <x-ui.text muted size="sm">
                System statistics and operational insights
            </x-ui.text>
        </div>


        {{-- ================= KPI CARDS ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Total Revenue --}}
            <x-ui.card>
                <x-slot name="content">
                    <x-ui.text size="sm" muted>Total Revenue</x-ui.text>
                    <x-ui.header h="3">
                        ${{ number_format($revenue['total'], 2) }}
                    </x-ui.header>
                </x-slot>
            </x-ui.card>

            {{-- Total Bookings --}}
            <x-ui.card>
                <x-slot name="content">
                    <x-ui.text size="sm" muted>Total Bookings</x-ui.text>
                    <x-ui.header h="3">
                        {{ $bookings['total'] }}
                    </x-ui.header>
                </x-slot>
            </x-ui.card>

            {{-- Active Rentals --}}
            <x-ui.card>
                <x-slot name="content">
                    <x-ui.text size="sm" muted>Active Rentals</x-ui.text>
                    <x-ui.header h="3">
                        {{ $bookings['active_now'] }}
                    </x-ui.header>
                </x-slot>
            </x-ui.card>

            {{-- Available Vehicles --}}
            <x-ui.card>
                <x-slot name="content">
                    <x-ui.text size="sm" muted>Available Vehicles</x-ui.text>
                    <x-ui.header h="3">
                        {{ $vehicles['available'] }}
                    </x-ui.header>
                </x-slot>
            </x-ui.card>

        </div>


        {{-- ================= BOOKING STATUS ================= --}}
        <x-ui.card>
            <x-slot name="header">
                <x-ui.header h="3">Booking Status Overview</x-ui.header>
            </x-slot>

            <x-slot name="content">
                <div class="flex flex-wrap gap-4">

                    <x-ui.badge variant="warning">
                        Pending: {{ $bookings['pending'] }}
                    </x-ui.badge>

                    <x-ui.badge variant="default">
                        Confirmed: {{ $bookings['confirmed'] }}
                    </x-ui.badge>

                    <x-ui.badge variant="success">
                        Completed: {{ $bookings['completed'] }}
                    </x-ui.badge>

                    <x-ui.badge variant="destructive">
                        Canceled: {{ $bookings['canceled'] }}
                    </x-ui.badge>

                </div>
            </x-slot>
        </x-ui.card>


        {{-- ================= REVENUE BREAKDOWN ================= --}}
        <x-ui.card>
            <x-slot name="header">
                <x-ui.header h="3">Revenue Breakdown</x-ui.header>
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <x-ui.text size="sm" muted>This Month</x-ui.text>
                        <x-ui.header h="3">
                            ${{ number_format($revenue['this_month'], 2) }}
                        </x-ui.header>
                    </div>

                    <div>
                        <x-ui.text size="sm" muted>Today</x-ui.text>
                        <x-ui.header h="3">
                            ${{ number_format($revenue['today'], 2) }}
                        </x-ui.header>
                    </div>

                    <div>
                        <x-ui.text size="sm" muted>Refunded</x-ui.text>
                        <x-ui.header h="3">
                            ${{ number_format($revenue['refunded'], 2) }}
                        </x-ui.header>
                    </div>

                </div>
            </x-slot>
        </x-ui.card>


        {{-- ================= INSIGHTS ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Most Booked Vehicle --}}
            <x-ui.card>
                <x-slot name="header">
                    <x-ui.header h="3">Most Booked Vehicle</x-ui.header>
                </x-slot>

                <x-slot name="content">
                    @if ($most_booked_vehicle)
                        <x-ui.header h="3">
                            {{ $most_booked_vehicle }}
                        </x-ui.header>
                    @else
                        <x-ui.text muted>No data available</x-ui.text>
                    @endif
                </x-slot>
            </x-ui.card>


            {{-- Payment Methods --}}
            <x-ui.card>
                <x-slot name="header">
                    <x-ui.header h="3">Payment Methods</x-ui.header>
                </x-slot>

                <x-slot name="content">

                    <x-ui.table>

                        <x-slot name="header">
                            <x-ui.table-tr>
                                <x-ui.table-th>Method</x-ui.table-th>
                                <x-ui.table-th>Count</x-ui.table-th>
                            </x-ui.table-tr>
                        </x-slot>

                        @foreach ($payment_methods as $method => $count)
                            <x-ui.table-tr>
                                <x-ui.table-td>
                                    {{ str_replace('_', ' ', ucfirst($method)) }}
                                </x-ui.table-td>
                                <x-ui.table-td>
                                    {{ $count }}
                                </x-ui.table-td>
                            </x-ui.table-tr>
                        @endforeach

                    </x-ui.table>

                </x-slot>
            </x-ui.card>

        </div>

    </div>

@endsection

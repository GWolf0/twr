@extends('layouts.mainLayout')

@section('title', 'My Bookings')

@section('content')
    <div class="space-y-6">
        <div class="space-y-2">
            <x-ui.header h="1">My Bookings</x-ui.header>
            <p class="text-muted-foreground">Manage your vehicle reservations and track their status.</p>
        </div>

        @if ($bookings->isEmpty())
            <x-ui.paper class="p-8 text-center space-y-4">
                <div class="text-muted-foreground">
                    <i class="bi bi-calendar-x text-5xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-medium">No bookings found</h3>
                    <p class="text-sm text-muted-foreground">You haven't made any reservations yet.</p>
                </div>
                <x-ui.button variant="primary" as="a" href="{{ route('common.page.search') }}">
                    Browse Vehicles
                </x-ui.button>
            </x-ui.paper>
        @else
            <x-ui.table>
                <x-slot:header>
                    <x-ui.table-th>ID</x-ui.table-th>
                    <x-ui.table-th>Vehicle</x-ui.table-th>
                    <x-ui.table-th>Start Date</x-ui.table-th>
                    <x-ui.table-th>End Date</x-ui.table-th>
                    <x-ui.table-th>Status</x-ui.table-th>
                    <x-ui.table-th>Payment</x-ui.table-th>
                    <x-ui.table-th>Amount</x-ui.table-th>
                    <x-ui.table-th class="text-right">Actions</x-ui.table-th>
                </x-slot:header>

                @foreach ($bookings as $booking)
                    <x-ui.table-tr>
                        <x-ui.table-td class="font-medium">#{{ $booking->id }}</x-ui.table-td>
                        <x-ui.table-td>{{ $booking->vehicle?->name ?? 'N/A' }}</x-ui.table-td>
                        <x-ui.table-td>{{ \Illuminate\Support\Carbon::parse($booking->start_date)->format('M d, Y H:i') }}</x-ui.table-td>
                        <x-ui.table-td>{{ \Illuminate\Support\Carbon::parse($booking->end_date)->format('M d, Y H:i') }}</x-ui.table-td>
                        <x-ui.table-td>
                            @php
                                $statusVariant = match($booking->status) {
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'canceled' => 'destructive',
                                    'completed' => 'secondary',
                                    default => 'default'
                                };
                            @endphp
                            <x-ui.badge :variant="$statusVariant" class="capitalize">
                                {{ $booking->status }}
                            </x-ui.badge>
                        </x-ui.table-td>
                        <x-ui.table-td>
                            @php
                                $paymentVariant = match($booking->payment_status) {
                                    'unpaid' => 'destructive',
                                    'paid' => 'success',
                                    'refunded' => 'warning',
                                    default => 'outline'
                                };
                            @endphp
                            <x-ui.badge :variant="$paymentVariant" class="capitalize">
                                {{ $booking->payment_status }}
                            </x-ui.badge>
                        </x-ui.table-td>
                        <x-ui.table-td class="font-semibold">
                            ${{ number_format($booking->total_amount, 2) }}
                        </x-ui.table-td>
                        <x-ui.table-td class="text-right">
                            <div class="flex justify-end gap-2">
                                <x-ui.button variant="outline" size="sm" as="a" href="{{ route('customer.page.booking_details', $booking->id) }}">
                                    Details
                                </x-ui.button>
                                
                                @if(!in_array($booking->status, ['canceled', 'completed']))
                                    <form action="{{ route('customer.action.cancel_booking', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                        @csrf
                                        <x-ui.button variant="destructive" size="sm" type="submit">
                                            Cancel
                                        </x-ui.button>
                                    </form>
                                @endif
                            </div>
                        </x-ui.table-td>
                    </x-ui.table-tr>
                @endforeach
            </x-ui.table>

            <div class="mt-4">
                <x-ui.pagination 
                    :currentPage="$bookings->currentPage()" 
                    :lastPage="$bookings->lastPage()" 
                    :query="request()->query()" 
                />
            </div>
        @endif
    </div>
@endsection

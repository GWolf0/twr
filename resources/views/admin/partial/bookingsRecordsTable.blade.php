@php
    $paginatedRecords = $models; // Illuminate\Pagination\LengthAwarePaginator
    $columns = [
        'id',
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'status',
        'payment_status',
        'payment_method',
        'total_amount',
        'created_at',
    ];
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2 items-center justify-between">
            <x-ui.header h="3">Bookings</x-ui.header>
            <div class="flex gap-2 items-center">
                <x-ui.button href="{{ route('admin.page.dashboard_record_create', ['table' => 'bookings']) }}">
                    <i class="bi bi-plus-lg"></i> Booking
                </x-ui.button>
            </div>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.table caption="Bookings {{ $paginatedRecords->count() }}">
            <x-slot:header>
                <x-ui.table-tr>
                    @foreach ($columns as $col)
                        <x-ui.table-th class="capitalize">{{ str_replace('_', ' ', $col) }}</x-ui.table-th>
                    @endforeach
                    <x-ui.table-th class="capitalize">Actions</x-ui.table-th>
                </x-ui.table-tr>
            </x-slot:header>

            @foreach ($paginatedRecords as $record)
                <x-ui.table-tr>
                    @foreach ($columns as $col)
                        <x-ui.table-td>
                            @if ($col === 'user_id')
                                {{ $record->user->name ?? 'Deleted User' }}
                            @elseif($col === 'vehicle_id')
                                {{ $record->vehicle->name ?? 'Deleted Vehicle' }}
                            @else
                                {{ $record[$col] }}
                            @endif
                        </x-ui.table-td>
                    @endforeach

                    <x-ui.table-td class="flex gap-2">
                        <x-ui.link
                            href="{{ route('admin.page.dashboard_record_edit', ['table' => 'bookings', 'id' => $record->id]) }}">
                            <x-ui.button size="icon-sm" variant="outline" title="edit">
                                <i class="bi bi-pencil-fill"></i>
                            </x-ui.button>
                        </x-ui.link>

                        <form action="{{ route('admin.action.delete_record', ['bookings', $record->id]) }}" method="POST"
                            data-confirm data-loading="..">
                            @csrf
                            @method('delete')
                            <x-ui.button type="submit" size="icon-sm" variant="destructive" title="remove">
                                <i class="bi bi-trash"></i>
                            </x-ui.button>
                        </form>
                    </x-ui.table-td>
                </x-ui.table-tr>
            @endforeach

        </x-ui.table>
    </x-slot:content>

    <x-slot:footer>
        <div class="flex items-center justify-center">
            <x-ui.pagination lastPage="{{ $paginatedRecords->lastPage() }}"
                currentPage="{{ $paginatedRecords->currentPage() }}" />
        </div>
    </x-slot:footer>

</x-ui.card>

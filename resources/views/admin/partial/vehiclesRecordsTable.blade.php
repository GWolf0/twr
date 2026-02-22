@php
    $paginatedRecords = $models; // Illuminate\Pagination\LengthAwarePaginator
    $columns = ['id', 'name', 'type', 'media', 'price_per_hour', 'availability', 'created_at', 'updated_at'];
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2 items-center justify-between">
            <x-ui.header h="3">Vehicles</x-ui.header>
            <div class="flex gap-2 items-center">
                <x-ui.button href="{{ route('admin.page.dashboard_record_create', ['table' => 'vehicles']) }}">
                    <i class="bi bi-plus-lg"></i> Vehicle
                </x-ui.button>
            </div>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.table caption="Vehicles {{ $paginatedRecords->count() }}">
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
                            @if ($col === 'media')
                                @php
                                    $media = explode(',', $record[$col]);
                                    $firstMedia = trim($media[0]);
                                @endphp
                                @if ($firstMedia)
                                    <img src="{{ $firstMedia }}" alt="{{ $record->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    No Image
                                @endif
                            @else
                                {{ $record[$col] }}
                            @endif
                        </x-ui.table-td>
                    @endforeach

                    <x-ui.table-td class="flex gap-2">
                        <x-ui.link
                            href="{{ route('admin.page.dashboard_record_edit', ['table' => 'vehicles', 'id' => $record->id]) }}">
                            <x-ui.button size="icon-sm" variant="outline" title="edit">
                                <i class="bi bi-pencil-fill"></i>
                            </x-ui.button>
                        </x-ui.link>

                        <form action="{{ route('admin.action.delete_record', ['vehicles', $record->id]) }}" method="POST"
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

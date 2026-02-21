<!--
users record table
- displays a table of users records
- the paginated records are stored in the variable "$models"
- the table must contain an additional column "actions" where a delete button to delete the record exists,and an edit button
- for the columns of foreign key fields, make sure to instead of showing the id, show a user frieldly field (eg. instead of "user_id" => "user->name")
- use the existing ui in "views/components/ui" to construct the ui as much as possible
- make sure to add a "create" button to created a new record above the table
- you can check the routes in "/routes/mainRoutes"
-->

@php
    $paginatedRecords = $models; // Illuminate\Pagination\LengthAwarePaginator
    $columns = ['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2 items-center justify-between">
            <x-ui.header h="3">Users</x-ui.header>
            <div class="flex gap-2 items-center">
                <x-ui.button href="{{ route('admin.page.dashboard_record_create', ['table' => 'users']) }}">
                    <i class="bi bi-plus-lg"></i> User
                </x-ui.button>
            </div>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.table caption="Users {{ $paginatedRecords->count() }}">
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
                        <x-ui.table-td>{{ $record[$col] }}</x-ui.table-td>
                    @endforeach

                    <x-ui.table-td class="flex gap-2">
                        <x-ui.link
                            href="{{ route('admin.page.dashboard_record_edit', ['table' => 'users', 'id' => $record->id]) }}">
                            <x-ui.button size="icon-sm" variant="outline" title="edit">
                                <i class="bi bi-pencil-fill"></i>
                            </x-ui.button>
                        </x-ui.link>

                        <form action="{{ route('admin.action.delete_record', ['users', $record->id]) }}" method="POST"
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

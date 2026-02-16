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
    $paginatedRecords = $models;
    $columns = ['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2 items-center justify-between">
            <h1>Users</h1>
            <div class="flex gap-2 items-center">
                <x-ui.link href="{{ route('admin.page.dashboard_record_create', ['table' => 'users']) }}">
                    <x-ui.button>
                        <i class="bi bi-plus-lg"> User
                    </x-ui.button>
                </x-ui.link>
            </div>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.table caption="Users {{ $paginatedUsers->total }}">
            <x-slot:header>
                <x-ui.table-tr>
                    @foreach ($columns as $col)
                        <x-ui.table-th class="capitalize">{{ str_replace($col, '_', ' ') }}</x-ui.table-th>
                    @endforeach
                    <x-ui.table-th class="capitalize">Actions</x-ui.table-th>
                </x-ui.table-tr>
            </x-slot:header>

            @foreach ($paginated->data as $record)
                <x-ui.table-tr>
                    @foreach ($columns as $col)
                        <x-ui.table-td>{{ $record[$col] }}</x-ui.table-td>
                    @endforeach

                    <x-ui.table-td class="flex gap-2">
                        <x-ui.link
                            href="{{ route('admin.page.dashboard_record_edit', ['table' => 'users', 'id' => $record->id]) }}">
                            <x-ui.button size="icon-md">
                                <i class="bi bi-pencil-fill"></i>
                            </x-ui.button>
                        </x-ui.link>
                    </x-ui.table-td>
                </x-ui.table-tr>
            @endforeach

        </x-ui.table>
    </x-slot:content>

    <x-slot:footer>
        <div class="flex items-center justify-center">
            <x-ui.pagination />
        </div>
    </x-slot:footer>

</x-ui.card>

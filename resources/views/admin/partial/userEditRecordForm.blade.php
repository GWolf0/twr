<!--
user edit record form
- displays a form to edit a user record
-->

@php
    use App\Misc\Enums\UserRole;
    use function App\Helpers\enumOptions;

    $record = $model;
    $rolesOptions = enumOptions(UserRole::class);
@endphp

<x-ui.card>

    <x-slot:header>
        <div class="flex gap-2">
            <x-ui.header h="3">Edit user</x-ui.header>
        </div>
    </x-slot:header>

    <x-slot:content>

        <x-ui.form method="PATCH"
            action="{{ route('admin.action.update_record', ['table' => 'users', 'id' => $record->id]) }}">

            <x-ui.form-group>
                <x-ui.error key="name" />
                <x-ui.label for="f_name">Name</x-ui.label>
                <x-ui.input id="f_name" name="name" placeholder="name" value="{{ old('name', $record->name) }}"
                    required minLength="3" maxLength="64" />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="email" />
                <x-ui.label for="f_email">Email</x-ui.label>
                <x-ui.input id="f_email" name="email" type="email" placeholder="email"
                    value="{{ old('email', $record->email) }}" required minLength="10" maxLength="128" />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="password" />
                <x-ui.label for="f_password">Password</x-ui.label>
                <x-ui.input id="f_password" name="password" type="password" placeholder="password" minLength="8"
                    maxLength="32" />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="password_confirmation" />
                <x-ui.label for="f_password_confirmation">Password Again</x-ui.label>
                <x-ui.input id="f_password_confirmation" name="password_confirmation" type="password"
                    placeholder="retype password" minLength="8" maxLength="32" />
            </x-ui.form-group>

            @php
                $roleValue = old('role', $record->role);
            @endphp
            <x-ui.form-group>
                <x-ui.error key="role" />
                <x-ui.label for="f_role">Role</x-ui.label>
                <x-ui.select id="f_role" name="role" :options="$rolesOptions" :initialValue="$roleValue" />
            </x-ui.form-group>

            <x-ui.form-actions>
                <x-ui.button type="reset" variant="outline">
                    Reset
                </x-ui.button>
                <x-ui.button type="submit">
                    Update
                </x-ui.button>
            </x-ui.form-actions>

        </x-ui.form>

    </x-slot:content>

</x-ui.card>

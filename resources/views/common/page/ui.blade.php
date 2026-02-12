@extends('layouts.mainLayout')
@section('innerClasses')
    flex flex-col gap-4
@endsection

@section('content')
    <h1 class="text-3xl">UI Page</h1>

    {{-- // color palette --}}
    <section class="space-y-4">
        <h2>Colors</h2>

        <div class="flex w-full flex-wrap gap-0.5">
            <div class="p-2 text-sm rounded border border-border bg-background text-foreground">Background</div>
            <div class="p-2 text-sm rounded border border-border bg-primary text-primary-foreground">Primary</div>
            <div class="p-2 text-sm rounded border border-border bg-secondary text-secondary-foreground">Secondary</div>
            <div class="p-2 text-sm rounded border border-border bg-muted text-muted-foreground">Muted</div>
            <div class="p-2 text-sm rounded border border-border bg-accent text-accent-foreground">Accent</div>
            <div class="p-2 text-sm rounded border border-border bg-card text-card-foreground">Card</div>
            <div class="p-2 text-sm rounded border border-border bg-destructive text-destructive-foreground">Destructive
            </div>
            <div class="p-2 text-sm rounded border border-border bg-border text-destructive-foreground">Border</div>
            <div class="p-2 text-sm rounded border border-border bg-input text-destructive-foreground">Input</div>
        </div>
    </section>

    {{-- // alerts --}}
    <section class="space-y-4">
        <h2>Alerts</h2>

        <div class="flex flex-col w-full gap-1">
            <x-ui.alert severity="info">
                This is an info message.
            </x-ui.alert>

            <x-ui.alert severity="success" :autoclose="false">
                Saved successfully.
            </x-ui.alert>

            <x-ui.alert severity="error" :closeBtn="false">
                Something went wrong.
            </x-ui.alert>

        </div>
    </section>

    {{-- // dropdown --}}
    <section class="space-y-4">
        <h2>Dropdown</h2>

        <div class="flex w-full gap-1">
            <x-ui.dropdown alignX="left">
                <x-slot:trigger>
                    <x-ui.button variant="outline">
                        Profile
                    </x-ui.button>
                </x-slot:trigger>

                <x-slot:content>
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-muted">Settings</a>
                    <a href="#" class="block px-4 py-2 text-sm hover:bg-muted">Logout</a>
                </x-slot:content>
            </x-ui.dropdown>

        </div>
    </section>

    {{-- // modals --}}
    <section class="space-y-4">
        <h2>Modal</h2>

        <div class="flex w-full gap-1">

            <x-ui.button onclick="openModal('deleteUserModal')">Open Modal</x-ui.button>

            <x-ui.modals-container>

                <x-ui.modal id="deleteUserModal" width="md">
                    <x-slot:header>
                        Delete User
                    </x-slot:header>

                    <x-slot:content>
                        Are you sure you want to delete this user?
                    </x-slot:content>

                    <x-slot:footer>
                        <x-ui.button variant="ghost" onclick="closeModal('deleteUserModal')">
                            Cancel
                        </x-ui.button>

                        <x-ui.button variant="destructive">
                            Delete
                        </x-ui.button>
                    </x-slot:footer>
                </x-ui.modal>

            </x-ui.modals-container>

        </div>
    </section>

    {{-- // toast --}}
    <section class="space-y-4">
        <h2>Toast</h2>

        <div class="flex w-full gap-1">

            <x-ui.toasts-container />
            <x-ui.button onclick="toast('Saved successfully', 'success')">Show Toast</x-ui.button>

        </div>
    </section>

    {{-- // Error --}}
    <section class="space-y-4">
        <h2>Error</h2>

        <div class="flex flex-col w-full gap-1">

            <x-ui.error error="Email already exists." />

            @php
                $errorArray = ['email' => 'Invalid email!'];
            @endphp
            <x-ui.error :error="$errorArray" key="email" />

        </div>
    </section>

    {{-- // Form --}}
    <section class="space-y-4">
        <h2>Form</h2>

        <div class="flex flex-col w-full gap-1">

            <x-ui action="{{ route('auth.action.register') }}" method="POST">

                <x-ui-group>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input name="email" type="email" />
                    <x-ui.error key="email" />
                </x-ui-group>

                <x-ui-group>
                    <x-ui.label for="role">Role</x-ui.label>
                    <x-ui.select name="role">
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </x-ui.select>
                    <x-ui.error key="role" />
                </x-ui-group>

                <x-ui-actions>
                    <x-ui type="reset" variant="secondary">Clear</x-ui>
                    <x-ui type="submit">Create User</x-ui>
                </x-ui-actions>

            </x-ui>

        </div>
    </section>

    <hr class="border-t border-muted" />

    {{-- // form elements --}}
    <section class="space-y-4">
        <h2>Form elements</h2>

        <div class="flex w-full flex-col gap-2">

            <x-ui.label for="email">Label element</x-ui.label>

            <x-ui.input name="email" type="email" placeholder="email" required />

            <x-ui.input name="file" type="file" />

            <x-ui.textarea name="text" rows="3">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Soluta aliquam eaque laboriosam, a qui dolores
                quae recusandae blanditiis voluptatum ipsa tenetur? Officiis voluptatem accusantium minus eligendi at
                consequuntur porro optio!
            </x-ui.textarea>

            @php
                $o = ['option 1' => 'val 1', 'option 2' => 'val 2', 'option 3' => 'val 3'];
            @endphp
            <x-ui.select :options="$o" />

            <x-ui.checkbox name="is_private" label="is private" checked />

        </div>
    </section>

    {{-- // layout --}}
    <section class="space-y-4">
        <h2>Layout</h2>

        <div class="flex w-full flex-col gap-2">

            <x-ui.paper>
                <p class="text-gray-800 text-sm">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Totam in aut tenetur praesentium dolores esse
                    consequuntur tempora nostrum natus maiores reprehenderit sit corrupti, nesciunt earum cum? Soluta
                    consequatur voluptatum quo.
                </p>
            </x-ui.paper>

            <x-ui.card>
                @slot('header')
                    <div class="space-y-1">
                        <h1 class="text-xl text-gray-800">Some Title</h1>
                        <h2 class="text-base text-gray-700">Some subtitle</h2>
                    </div>
                @endslot
                @slot('content')
                    <div class="space-y-2">
                        <p class="text-sm text-gray-800">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque quam iusto vel fugit, provident
                            nobis, tempora vero aperiam reiciendis nemo magnam voluptate cupiditate esse doloribus repudiandae
                            est ullam dicta minima.
                        </p>
                    </div>
                @endslot
                @slot('footer')
                    <div class="flex items-center justify-end gap-4">
                        <x-ui.button>Button</x-ui.button>
                    </div>
                @endslot
            </x-ui.card>

        </div>

    </section>

    {{-- // Buttons --}}
    <section class="space-y-4">
        <h2>Buttons</h2>

        <div class="flex flex-wrap w-full flex-row gap-2 items-center">

            <x-ui.button variant="primary" size="lg" class="">Primary</x-ui.button>
            <x-ui.button variant="primary" size="icon-lg" class="">P</x-ui.button>
            <x-ui.button variant="primary" size="md" class="">Primary</x-ui.button>
            <x-ui.button variant="primary" size="icon-md" class="">P</x-ui.button>
            <x-ui.button variant="primary" size="sm" class="">Primary</x-ui.button>
            <x-ui.button variant="primary" size="icon-sm" class="">P</x-ui.button>

            <x-ui.button variant="secondary" size="md" class="">Secondary</x-ui.button>
            <x-ui.button variant="outline" size="md" class="">Outline</x-ui.button>
            <x-ui.button variant="ghost" size="md" class="">Ghost</x-ui.button>
            <x-ui.button variant="link" size="md" class="">Link</x-ui.button>

        </div>

    </section>
@endsection

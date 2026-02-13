@php
    use Illuminate\Support\Str;
@endphp

<header class="w-full h-20 border-b border-border bg-background flex items-center justify-between px-4 md:px-6">
    <x-layout.logo />

    <div class="flex items-center gap-3">
        @guest
            <x-ui.button href="{{ route('auth.page.login') }}">
                Login
            </x-ui.button>
        @endguest

        @auth
            <x-ui.dropdown alignX="right">
                <x-slot:trigger>
                    <x-ui.button variant="outline" size="icon-md" class="rounded-full text-lg"
                        title="{{ auth()->user()->name }}">
                        {{ strtoupper(auth()->user()->name[0] ?? '') }}
                    </x-ui.button>
                </x-slot:trigger>

                <x-slot:content>
                    <x-ui.link href="{{ route('customer.page.bookings_list') }}">
                        My Bookings
                    </x-ui.link>

                    <x-ui.link href="{{ route('customer.page.profile') }}">
                        My Profile
                    </x-ui.link>

                    <form action="{{ route('auth.action.logout') }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="destructive" class="w-full text-left">
                            Logout
                        </x-ui.button>
                    </form>
                </x-slot:content>
            </x-ui.dropdown>
        @endauth
    </div>
</header>

@php
    use Illuminate\Support\Str;
@endphp

<header class="w-full h-20 border-b border-border bg-background flex items-center justify-between px-4 md:px-6">
    <x-layout.logo linkToHomePage />

    <div class="flex items-center gap-3">
        @guest
            <x-ui.button href="{{ route('auth.page.login') }}">
                {{ __('common.login') }}
            </x-ui.button>
        @endguest

        @auth
            <x-ui.dropdown alignX="right">
                <x-slot:trigger>
                    <x-ui.avatar name="{{ auth()->user()->name }}" />
                </x-slot:trigger>

                <x-slot:content>
                    <div class="flex flex-col gap-1 px-2 py-4">
                        <x-ui.link href="{{ route('customer.page.bookings_list') }}">
                            <x-ui.button variant="ghost" class="w-full">
                                {{ __('common.my_bookings') }}
                            </x-ui.button>
                        </x-ui.link>

                        <x-ui.link href="{{ route('customer.page.profile') }}">
                            <x-ui.button variant="ghost" class="w-full">
                                {{ __('common.my_profile') }}
                            </x-ui.button>
                        </x-ui.link>

                        <form action="{{ route('auth.action.logout') }}" method="POST">
                            @csrf
                            <x-ui.button type="submit" variant="destructive" class="w-full">
                                {{ __('common.logout') }}
                            </x-ui.button>
                        </form>
                    </div>
                </x-slot:content>
            </x-ui.dropdown>
        @endauth
    </div>
</header>

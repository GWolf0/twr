@extends('layouts.mainLayout')

@section('content')
    <main class="w-full grow flex items-center justify-center">
        @include('auth.partial.loginBox')
    </main>

    {{-- DEMO mode notification --}}
    @if (config('app.demo'))
        <section class="w-full mt-8">
            <x-ui.paper>
                <x-ui.text size="sm" muted class="text-primary">
                    Use these credentials to test the app
                </x-ui.text>

                <x-ui.separator class="my-4" />

                <table>
                    <tr>
                        <td class="font-bold text-sm p-2">- Admin</td>
                        <td>
                            admin@email.com | password
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold text-sm p-2">- Customer</td>
                        <td>
                            customer@email.com | password
                        </td>
                    </tr>
                </table>
            </x-ui.paper>
        </section>
    @endif
@endsection

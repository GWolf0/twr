@php
    $user = auth()->user();
@endphp

@if ($user && !$user->hasVerifiedEmail())
    <x-ui.alert severity="warning" :closeBtn="false" class="mb-4"
        message="Your email address is not verified. Please confirm your email to unlock all features.">

        <x-slot name="action">
            <form method="POST" action="{{ route('auth.action.send_email_confirmation_notification') }}">
                @csrf

                <x-ui.button type="submit" size="sm" variant="secondary">
                    Resend confirmation
                </x-ui.button>
            </form>
        </x-slot>

    </x-ui.alert>
@endif

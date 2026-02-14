<x-ui.card class="w-full max-w-md p-6 sm:p-8 space-y-6">
    <div class="flex flex-col items-center gap-4">
        <x-layout.logo size="lg" />
        <h2 class="text-2xl font-bold text-foreground">Reset your password</h2>
        <p class="text-sm text-muted-foreground text-center">
            Enter your new password below.
        </p>
    </div>

    <x-ui.form action="{{ route('auth.action.reset_password') }}" method="POST" class="space-y-4">
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <x-ui.form-group>
            <x-ui.label for="password" value="New Password" />
            <x-ui.input type="password" id="password" name="password" placeholder="••••••••" required />
        </x-ui.form-group>

        <x-ui.form-group>
            <x-ui.label for="password_confirmation" value="Confirm New Password" />
            <x-ui.input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required />
        </x-ui.form-group>

        <x-ui.button type="submit" class="w-full">
            Reset Password
        </x-ui.button>
    </x-ui.form>
</x-ui.card>
<x-ui.card class="w-full max-w-md p-6 sm:p-8 space-y-6">
    <x-slot:header>
        <div class="flex flex-col items-center gap-4">
            <x-layout.logo size="lg" />
            <h2 class="text-2xl font-bold text-foreground">Forgot your password?</h2>
            <p class="text-sm text-muted-foreground text-center">
                Enter your email address below and we'll send you a link to reset your password.
            </p>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.form action="{{ route('auth.action.send_password_reset_notification') }}" method="POST">
            <x-ui.form-group>
                <x-ui.error key="email" />
                <x-ui.label for="email" value="Email">Email</x-ui.label>
                <x-ui.input type="email" id="email" name="email" placeholder="john.doe@example.com" required
                    autofocus minLength=10 maxLength=128 />
            </x-ui.form-group>

            <x-ui.button type="submit" class="w-full">
                Send Reset Link
            </x-ui.button>
        </x-ui.form>

        <div class="text-center text-sm text-muted-foreground">
            Remember your password?
            <x-ui.link href="{{ route('auth.page.login') }}" class="font-medium">
                Login
            </x-ui.link>
        </div>
    </x-slot:content>
</x-ui.card>

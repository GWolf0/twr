<x-ui.card class="w-full max-w-md p-6 sm:p-8 space-y-6">
    <x-slot:header>
        <div class="flex flex-col items-center gap-4">
            <x-layout.logo size="lg" />
            <h2 class="text-2xl font-bold text-foreground">Sign in to your account</h2>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.form action="{{ route('auth.action.login') }}" method="POST">
            <x-ui.form-group>
                <x-ui.error key="email" />
                <x-ui.label for="email" value="Email">Email</x-ui.label>
                <x-ui.input type="email" id="email" name="email" placeholder="john.doe@example.com"
                    value="{{ old('email') }}" required minLength=10 maxLength=64 autofocus />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="password" />
                <x-ui.label for="password" value="Password">Password</x-ui.label>
                <x-ui.input type="password" id="password" name="password" placeholder="••••••••" required minLength=8
                    maxLength=32 />
            </x-ui.form-group>

            <div class="flex items-center justify-between">
                <x-ui.checkbox id="remember" name="remember" label="Remember me" />
                <x-ui.link href="{{ route('auth.page.forgot_password') }}" class="text-sm">
                    Forgot password?
                </x-ui.link>
            </div>

            <x-ui.button type="submit" class="w-full">
                Login
            </x-ui.button>
        </x-ui.form>

        <div class="text-center text-sm text-muted-foreground">
            Don't have an account?
            <x-ui.link href="{{ route('auth.page.register') }}" class="font-medium">
                Register
            </x-ui.link>
        </div>
    </x-slot:content>
</x-ui.card>

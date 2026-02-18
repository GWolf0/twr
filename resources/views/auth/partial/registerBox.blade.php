<x-ui.card class="w-full max-w-md p-6 sm:p-8 space-y-6">
    <x-slot:header>
        <div class="flex flex-col items-center gap-4">
            <x-layout.logo size="lg" />
            <h2 class="text-2xl font-bold text-foreground">Create a new account</h2>
        </div>
    </x-slot:header>

    <x-slot:content>
        <x-ui.form action="{{ route('auth.action.register') }}" method="POST">
            <x-ui.form-group>
                <x-ui.error key="name" />
                <x-ui.label for="name" value="Name">Name</x-ui.label>
                <x-ui.input type="text" id="name" name="name" placeholder="John Doe"
                    value="{{ old('name') }}" required minLength=3 maxLength=32 autofocus />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="email" />
                <x-ui.label for="email" value="Email">Email</x-ui.label>
                <x-ui.input type="email" id="email" name="email" placeholder="john.doe@example.com"
                    value="{{ old('email') }}" required minLength=10 maxLength=64 />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="password" />
                <x-ui.label for="password" value="Password">Password</x-ui.label>
                <x-ui.input type="password" id="password" name="password" placeholder="••••••••" required minLength=8
                    maxLength=32 />
            </x-ui.form-group>

            <x-ui.form-group>
                <x-ui.error key="password_confirmation" />
                <x-ui.label for="password_confirmation" value="Confirm Password">Retype password</x-ui.label>
                <x-ui.input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="••••••••" required minLength=8 maxLength=32 />
            </x-ui.form-group>

            <x-ui.button type="submit" class="w-full">
                Register
            </x-ui.button>
        </x-ui.form>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <x-ui.link href="{{ route('auth.page.login') }}" class="font-medium">
                Login
            </x-ui.link>
        </div>
    </x-slot:content>
</x-ui.card>

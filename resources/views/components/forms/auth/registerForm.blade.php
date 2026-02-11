<form action="{{route('auth.action.register')}}" method="post" class="space-y-4">
    {{-- // prepend --}}
    <div class="flex flex-col items-center justify-center gap-4 p-10">
        <div class="space-y-2">
            <h1 class="text-xl text-center">Register</h1>
            <p class="text-gray-700 text-center">Welcome to {{ config('app.name') }}</p>
        </div>
        <x-layout.logo />
    </div>

    {{-- // form elements --}}
    <div class="space-y-4">
        @csrf
    
        <div class="flex flex-col gap-2">
            <label for="" class="text-sm">*Email</label>
            <input type="text" class="w-full p-2 border border-gray-200 rounded" placeholder="email">
        </div>
    
        <div class="flex flex-col gap-2">
            <label for="" class="text-sm">*Password</label>
            <input type="text" class="w-full p-2 border border-gray-200 rounded" placeholder="email">
        </div>
    
        <div class="flex flex-col gap-2">
            <label for="" class="text-sm">*Confirm Password</label>
            <input type="text" class="w-full p-2 border border-gray-200 rounded" placeholder="email">
        </div>
    
        <div class="flex gap-4 justify-end items-center">
            <button class="w-full rounded bg-red-400 text-gray-100 p-2">Register</button>
        </div>

        <div class="flex justify-center">
            <p class="text-xs">By registering you agree with <a class="text-red-400" href="#">terms of use</a></p>
        </div>
    </div>

    <hr class="border-t border-gray-200" />

    {{-- // append --}}
    <div class="flex gap-4 justify-center">
        <a class="text-red-400" href="{{route('auth.page.login')}}">Already have an account? Login</a>
    </div>

</form>
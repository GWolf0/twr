@extends('layouts.mainLayout')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="space-y-2">
            <x-ui.header h="1">My Profile</x-ui.header>
            <p class="text-muted-foreground">Manage your personal information and account preferences.</p>
        </div>

        <x-ui.card padding="lg">
            <x-slot:header>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <x-ui.avatar :name="$profile->name" size="xl" />
                    <div class="text-center md:text-left space-y-1">
                        <h2 class="text-2xl font-semibold">{{ $profile->name }}</h2>
                        <div class="flex items-center justify-center md:justify-start gap-2 text-muted-foreground">
                            <i class="bi bi-envelope"></i>
                            <span>{{ $profile->email }}</span>
                        </div>
                    </div>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Account Role</p>
                        <div class="flex items-center gap-2">
                            <x-ui.badge variant="secondary" class="capitalize">
                                {{ $profile->role }}
                            </x-ui.badge>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Member Since</p>
                        <div class="flex items-center gap-2 text-foreground font-medium">
                            <i class="bi bi-calendar-event"></i>
                            <span>{{ $profile->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Email Status</p>
                        <div class="flex items-center gap-2">
                            @if ($profile->email_verified_at)
                                <x-ui.badge variant="success" class="gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Verified
                                </x-ui.badge>
                            @else
                                <x-ui.badge variant="destructive" class="gap-1">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Unverified
                                </x-ui.badge>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Language</p>
                        <p class="text-foreground font-medium">English (Default)</p>
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="flex justify-end pt-4">
                    <x-ui.button variant="outline" class="gap-2">
                        <i class="bi bi-pencil"></i>
                        Edit Profile
                    </x-ui.button>
                </div>
            </x-slot:footer>
        </x-ui.card>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-ui.paper class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 text-primary rounded-lg">
                        <i class="bi bi-shield-lock text-xl"></i>
                    </div>
                    <h3 class="font-semibold">Security</h3>
                </div>
                <p class="text-sm text-muted-foreground">Keep your account secure by updating your password regularly.</p>
                <x-ui.button variant="ghost" size="sm" class="px-0 text-primary hover:text-primary hover:bg-transparent">
                    Change Password &rarr;
                </x-ui.button>
            </x-ui.paper>

            <x-ui.paper class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-secondary/10 text-secondary rounded-lg">
                        <i class="bi bi-bell text-xl"></i>
                    </div>
                    <h3 class="font-semibold">Notifications</h3>
                </div>
                <p class="text-sm text-muted-foreground">Manage how you receive booking updates and promotional offers.</p>
                <x-ui.button variant="ghost" size="sm" class="px-0 text-secondary hover:text-secondary hover:bg-transparent">
                    Notification Preferences &rarr;
                </x-ui.button>
            </x-ui.paper>
        </div>
    </div>
@endsection

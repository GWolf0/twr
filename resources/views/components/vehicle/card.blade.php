@props([
    'vehicle',
])

@php
    $images = array_filter(explode(',', $vehicle->media ?? ''));
    $firstImage = count($images) > 0 ? $images[0] : 'https://placehold.co/600x400?text=No+Image';
    $isAvailable = $vehicle->availability === \App\Misc\Enums\VehicleAvailability::available->name;

    $badgeVariant = match ($vehicle->availability) {
        \App\Misc\Enums\VehicleAvailability::available->name => 'success',
        \App\Misc\Enums\VehicleAvailability::unavailable->name => 'destructive',
        \App\Misc\Enums\VehicleAvailability::maintenance->name => 'warning',
        default => 'secondary',
    };
@endphp

<x-ui.paper {{ $attributes->merge(['class' => 'flex flex-col h-full overflow-hidden']) }} padding="none">
    <div class="relative aspect-video overflow-hidden">
        <img src="{{ $firstImage }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
        <div class="absolute top-2 right-2">
            <x-ui.badge :variant="$badgeVariant">
                {{ ucfirst($vehicle->availability) }}
            </x-ui.badge>
        </div>
    </div>
    <div class="p-4 flex-grow flex flex-col space-y-2">
        <div>
            <x-ui.header h="3">{{ $vehicle->name }}</x-ui.header>
            <x-ui.text size="sm" muted>{{ $vehicle->type }}</x-ui.text>
        </div>

        <div class="mt-auto pt-4 flex items-center justify-between">
            <div>
                <x-ui.text size="lg" class="font-bold text-primary">
                    ${{ number_format($vehicle->price_per_hour, 2) }}
                    <span class="text-xs font-normal text-muted-foreground">/ hr</span>
                </x-ui.text>
            </div>
            <x-ui.button variant="primary" size="sm"
                :href="route('common.page.vehicle_details', ['vehicle_id' => $vehicle->id])">
                Details
            </x-ui.button>
        </div>
    </div>
</x-ui.paper>

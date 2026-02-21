<!--
vehicle-images-field ui:
- displays images of a particular vehicle in a grid (maximum = 7 images)
- allows removing images attached to the particular vehicle (add button to trigger that)
- triggers file upload manager to upload/select images to add as images for the vehicle
-->

@props([
    'media' => '', // media are images (urls of images file) passed as csv
])

@php
    $images = explode(',', $media);
    $imagesCount = count($images);
@endphp

<x-ui.form-group>
    <x-ui.error key="images" />
    <x-ui.label>Images</x-ui.label>
    <input type="hidden" name="media" value="{{ $media }}" />

    @if ($imagesCount < 1)
        <x-ui.text muted>No images assigned<x-ui.text>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        {{-- // button to triggen file upload manager --}}
        <div class="aspect-square rounded-md bg-card border border-border flex items-center justify-center text-card-foreground text-2xl transition hover:opacity-70 cursor-pointer"
            data-role="button" onclick="onPlusImage">
            <i class="bi bi-plus-lg"></i>
        </div>

        {{-- // vehicle's images cards --}}
        @foreach ($images as $i => $image)
            <div class="relative aspect-square rounded-md overflow-hidden bg-card border border-border">
                {{-- // remove btn --}}
                <x-ui.button size="icon-sm" variant="destructive" class="absolure right-1 top-1"
                    onclick="onRemoveImage({{ $i }})">
                    <i class="bi bi-trash"></i>
                </x-ui.button>

                <div class="bg-black opacity-5 hover:opacity-10">
                    <img class="aspect-square object-contain" src="{{ $image }}" />
                </div>
            </div>
        @endforeach
    </div>
</x-ui.form-group>

{{-- // script --}}
<script>
    // on received selected images callback
    function onReceivedImages(images) {

    }

    function onPlusImage() {
        window.openFileUploadModal(onReceivedImages);
    }

    function onRemoveImage(imageIdx) {

    }
</script>

{{--
|--------------------------------------------------------------------------
| Vehicle Images Field Component
|--------------------------------------------------------------------------
| Usage:
| <x-vehicle-images-field :media="$vehicle->media ?? ''" />
|
| Description:
| - Displays vehicle images in a responsive grid
| - Allows removing images
| - Opens FileUploadManager to select/upload images
| - Stores selected image URLs as CSV in hidden "media" input
| - Max allowed images: 7
|
| Requirements:
| - FileUploadManager.js must be loaded globally
| - FileUpload modal must exist in layout
|--------------------------------------------------------------------------
--}}

@props([
    'media' => '',
])

@php
    $oldMedia = old('media', $media);
    $images = array_filter(explode(',', $oldMedia));
@endphp

<x-ui.form-group>
    <x-ui.error key="media" />
    <x-ui.label>Images</x-ui.label>

    <input type="hidden" name="media" id="vehicle-media-input" value="{{ implode(',', $images) }}" />

    <div id="vehicle-images-wrapper" class="grid grid-cols-2 md:grid-cols-4 gap-3">
    </div>

    <x-ui.text muted class="mt-2">
        Maximum 7 images allowed.
    </x-ui.text>
</x-ui.form-group>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const MAX_IMAGES = 7;

        let images = @json(array_values($images));

        const wrapper = document.getElementById("vehicle-images-wrapper");
        const hiddenInput = document.getElementById("vehicle-media-input");

        /* ----------------------------------------
           RENDER GRID
        ---------------------------------------- */
        function render() {
            wrapper.innerHTML = "";

            // PLUS BUTTON (if limit not reached)
            if (images.length < MAX_IMAGES) {
                const plusBtn = document.createElement("div");
                plusBtn.className =
                    "aspect-square rounded-md bg-card border border-border flex items-center justify-center text-2xl cursor-pointer transition hover:opacity-70";

                plusBtn.innerHTML = `<i class="bi bi-plus-lg"></i>`;
                plusBtn.addEventListener("click", openFileManager);

                wrapper.appendChild(plusBtn);
            }

            // IMAGE CARDS
            images.forEach((url, index) => {
                const card = document.createElement("div");
                card.className =
                    "relative aspect-square rounded-md overflow-hidden bg-card border border-border";

                card.innerHTML = `
                <img src="${url}"
                     class="w-full h-full object-contain bg-black/5" />

                <button type="button"
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs hover:opacity-80">
                    <i class="bi bi-trash"></i>
                </button>
            `;

                card.querySelector("button").addEventListener("click", () => {
                    removeImage(index);
                });

                wrapper.appendChild(card);
            });

            syncInput();
        }

        /* ----------------------------------------
           OPEN FILE MANAGER
        ---------------------------------------- */
        function openFileManager() {
            window.openFileUploadModal(function(selectedFiles) {

                selectedFiles.forEach(file => {
                    if (images.length >= MAX_IMAGES) return;

                    if (!images.includes(file.url)) {
                        images.push(file.url);
                    }
                });

                render();
            });
        }

        /* ----------------------------------------
           REMOVE IMAGE
        ---------------------------------------- */
        function removeImage(index) {
            images.splice(index, 1);
            render();
        }

        /* ----------------------------------------
           SYNC HIDDEN INPUT
        ---------------------------------------- */
        function syncInput() {
            hiddenInput.value = images.join(",");
        }

        render();
    });
</script>

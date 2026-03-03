<x-ui.modal id="fileUploadModal" width="xl">
    <x-slot:header>
        File Upload Manager
    </x-slot:header>

    <x-slot:content>
        <div id="fum-root" class="space-y-6">

            {{-- Storage Usage --}}
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-muted-foreground">Storage Usage</span>
                    <span id="fum-usage-text" class="text-muted-foreground"></span>
                </div>
                <x-ui.progress id="fum-usage-progress" :value="0" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">

                <div class="flex gap-3">
                    <x-ui.button type="button" data-fum-action onclick="FileUploadManager.triggerFileInput()">
                        Upload
                    </x-ui.button>

                    <x-ui.button type="button" variant="destructive" data-fum-action data-fum-delete disabled
                        onclick="FileUploadManager.deleteSelected()">
                        Delete Selected
                    </x-ui.button>
                </div>

                <x-ui.button type="button" variant="primary" data-fum-action data-fum-choose disabled
                    onclick="FileUploadManager.returnSelected()">
                    Choose
                </x-ui.button>

            </div>

            {{-- Hidden File Input --}}
            <input type="file" id="fum-file-input" multiple class="hidden" />

            {{-- Dropzone + Grid --}}
            <div id="fum-dropzone" class="border-2 border-dashed border-border rounded-lg p-4 transition-colors">

                <div id="fum-files-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                </div>

            </div>

        </div>
    </x-slot:content>

    <x-slot:footer>
        <x-ui.button type="button" variant="secondary" onclick="closeFileUploadModal()">
            Close
        </x-ui.button>
    </x-slot:footer>
</x-ui.modal>

<x-ui.modal id="fileUploadModal" width="xl">
    <x-slot:header>
        File Upload Manager
    </x-slot:header>

    <x-slot:content>
        <div id="fum-root" class="space-y-6">

            {{-- Usage Progress --}}
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
                    <x-ui.button type="button" onclick="FileUploadManager.triggerFileInput()">
                        Upload
                    </x-ui.button>

                    <x-ui.button type="button" variant="destructive" onclick="FileUploadManager.deleteSelected()">
                        Delete Selected
                    </x-ui.button>
                </div>

                <x-ui.button type="button" variant="primary" onclick="FileUploadManager.returnSelected()">
                    Choose
                </x-ui.button>
            </div>

            {{-- Hidden input --}}
            <input type="file" id="fum-file-input" multiple class="hidden" />

            {{-- Files Grid --}}
            <div id="fum-files-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>

        </div>
    </x-slot:content>

    <x-slot:footer>
        <x-ui.button type="button" variant="secondary" onclick="closeFileUploadModal()">
            Close
        </x-ui.button>
    </x-slot:footer>
</x-ui.modal>

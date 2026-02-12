<div id="modals-container" data-role="modals-container" class="fixed inset-0 z-100 hidden items-center justify-center">
    {{-- Backdrop --}}
    <div data-role="modal-backdrop"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    {{-- Modals slot --}}
    <div class="relative z-10 w-full h-full flex items-center justify-center p-4">
        {{ $slot }}
    </div>
</div>

/**
 * FileUploadManager
 */

const FILE_UPLOAD_MODAL_ID = "fileUploadModal";

const FileUploadManager = (function () {

    let files = [];
    let selected = new Set();
    let onSelectCallback = null;
    let options = { accept: "file", multiple: true };
    let isLoading = false;
    let initialized = false;

    /* --------------------------
       ELEMENT GETTERS
    --------------------------- */
    const gridEl = () => document.getElementById("fum-files-grid");
    const fileInputEl = () => document.getElementById("fum-file-input");
    const usageTextEl = () => document.getElementById("fum-usage-text");
    const usageProgressEl = () => document.getElementById("fum-usage-progress");
    const dropzoneEl = () => document.getElementById("fum-dropzone");

    const getApiToken = () =>
        document.querySelector('meta[name="api-token"]')?.content;

    const getCsrfToken = () =>
        document.querySelector('meta[name="csrf-token"]')?.content;

    /* --------------------------
       INIT
    --------------------------- */
    function init() {
        if (initialized) return;
        initialized = true;

        fileInputEl()?.addEventListener("change", handleFileInputChange);
        initDropzone();
    }

    /* --------------------------
       LOADING STATE
    --------------------------- */
    function setLoading(state) {
        isLoading = state;

        document.querySelectorAll("[data-fum-action]").forEach(btn => {
            btn.disabled = state;
            btn.classList.toggle("opacity-50", state);
            btn.classList.toggle("pointer-events-none", state);
        });
    }

    function updateActionButtons() {
        const deleteBtn = document.querySelector("[data-fum-delete]");
        const chooseBtn = document.querySelector("[data-fum-choose]");

        if (deleteBtn) deleteBtn.disabled = selected.size === 0 || isLoading;
        if (chooseBtn) chooseBtn.disabled = selected.size === 0 || isLoading;
    }

    /* --------------------------
       FETCH FILES
    --------------------------- */
    async function fetchFiles() {
        setLoading(true);

        try {
            const res = await fetch("/api/v1/file-upload", {
                headers: {
                    "ACCEPT": "application/json",
                    "AUTHORIZATION": "Bearer " + getApiToken(),
                }
            });

            if (!res.ok) throw new Error("Failed to fetch files");

            const data = await res.json();

            files = data.files || [];
            selected.clear();

            renderFiles();
            updateUsage(data.usage || {});
            updateActionButtons();

        } catch (e) {
            alert("Failed to load files.");
            console.warn(e);
        }

        setLoading(false);
    }

    /* --------------------------
       RENDER FILES
    --------------------------- */
    function renderFiles() {
        const grid = gridEl();
        grid.innerHTML = "";

        if (!files.length) {
            grid.innerHTML = `
                <div class="col-span-full text-center py-10 text-muted-foreground">
                    No files uploaded yet.
                </div>
            `;
            return;
        }

        files.forEach(file => {
            const item = document.createElement("div");

            item.className =
                "relative border rounded-lg overflow-hidden cursor-pointer group transition";

            item.dataset.id = file.id;

            item.innerHTML = `
                <img src="${file.url}"
                     class="w-full h-28 object-cover" />

                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>

                <div class="absolute top-2 right-2 hidden fum-checkbox">
                    <div class="w-5 h-5 bg-primary text-white text-xs flex items-center justify-center rounded">
                        ✓
                    </div>
                </div>
            `;

            item.addEventListener("click", () => toggleSelect(file.id, item));

            grid.appendChild(item);
        });
    }

    /* --------------------------
       SELECT
    --------------------------- */
    function toggleSelect(id, el) {
        if (isLoading) return;

        const checkbox = el.querySelector(".fum-checkbox");

        if (selected.has(id)) {
            selected.delete(id);
            el.classList.remove("ring-2", "ring-primary");
            checkbox.classList.add("hidden");
        } else {
            if (!options.multiple) {
                selected.clear();
                document.querySelectorAll(".fum-checkbox")
                    .forEach(cb => cb.classList.add("hidden"));
                document.querySelectorAll(".ring-primary")
                    .forEach(r => r.classList.remove("ring-2", "ring-primary"));
            }

            selected.add(id);
            el.classList.add("ring-2", "ring-primary");
            checkbox.classList.remove("hidden");
        }

        updateActionButtons();
    }

    /* --------------------------
       UPLOAD
    --------------------------- */
    function triggerFileInput() {
        if (isLoading) return;
        fileInputEl().click();
    }

    async function handleFileInputChange(e) {
        const filesToUpload = e.target.files;
        if (!filesToUpload.length || isLoading) return;

        setLoading(true);

        for (let file of filesToUpload) {
            addUploadingPlaceholder(file);
        }

        const formData = new FormData();
        for (let file of filesToUpload) {
            formData.append("files[]", file);
        }

        try {
            const res = await fetch("/api/v1/file-upload/many", {
                method: "POST",
                body: formData,
                headers: {
                    "ACCEPT": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                    "AUTHORIZATION": "Bearer " + getApiToken(),
                },
            });

            if (!res.ok) throw new Error("Upload failed");

            await res.json();
            await fetchFiles();

        } catch (e) {
            alert("Upload failed. Please try again.");
            console.warn(e);
        }

        fileInputEl().value = "";
        setLoading(false);
    }

    function addUploadingPlaceholder() {
        const grid = gridEl();

        const item = document.createElement("div");
        item.className =
            "relative border rounded-lg overflow-hidden bg-muted animate-pulse";

        item.innerHTML = `
            <div class="w-full h-28 flex items-center justify-center text-xs text-muted-foreground">
                Uploading...
            </div>
        `;

        grid.prepend(item);
    }

    /* --------------------------
       DELETE
    --------------------------- */
    async function deleteSelected() {
        if (!selected.size || isLoading) return;

        setLoading(true);

        const ids = Array.from(selected);

        try {
            const res = await fetch(`/api/v1/file-upload/${ids.join(",")}`, {
                method: "DELETE",
                headers: {
                    "ACCEPT": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                    "AUTHORIZATION": "Bearer " + getApiToken(),
                },
            });

            if (!res.ok) throw new Error("Delete failed");

            selected.clear();
            await fetchFiles();

        } catch (e) {
            alert("Delete failed.");
            console.warn(e);
        }

        setLoading(false);
    }

    /* --------------------------
       RETURN SELECTED
    --------------------------- */
    function returnSelected() {
        if (!selected.size || isLoading) return;

        if (onSelectCallback) {
            const selectedFiles = files.filter(f => selected.has(f.id));
            onSelectCallback(selectedFiles);
        }

        selected.clear();
        updateActionButtons();
        closeModal(FILE_UPLOAD_MODAL_ID);
    }

    /* --------------------------
       STORAGE USAGE
    --------------------------- */
    function updateUsage(usage) {
        if (!usage.total || !usage.used) return;

        const percent = Math.round((usage.used / usage.total) * 100);

        usageTextEl().innerText =
            `${formatBytes(usage.used)} / ${formatBytes(usage.total)}`;
        console.log(usage.total, usage.used, percent)
        usageProgressEl()
            .querySelector("div")
            .style.width = percent + "%";
    }

    function formatBytes(bytes) {
        const sizes = ["Bytes", "KB", "MB", "GB"];
        if (bytes === 0) return "0 Byte";
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return Math.round(bytes / Math.pow(1024, i)) + " " + sizes[i];
    }

    /* --------------------------
       DROPZONE
    --------------------------- */
    function initDropzone() {
        const dropzone = dropzoneEl();
        if (!dropzone) return;

        dropzone.addEventListener("dragover", e => {
            e.preventDefault();
            dropzone.classList.add("bg-muted");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("bg-muted");
        });

        dropzone.addEventListener("drop", e => {
            e.preventDefault();
            dropzone.classList.remove("bg-muted");

            const dt = e.dataTransfer;
            if (dt.files.length) {
                fileInputEl().files = dt.files;
                handleFileInputChange({ target: fileInputEl() });
            }
        });
    }

    /* --------------------------
       OPEN / CLOSE
    --------------------------- */
    function open(callback = null, _options = { accept: "file", multiple: true }) {
        onSelectCallback = callback;
        options = _options;

        fileInputEl().accept = _options.accept;
        fileInputEl().multiple = _options.multiple;

        selected.clear();
        updateActionButtons();

        fetchFiles();
        openModal(FILE_UPLOAD_MODAL_ID);
    }

    function close() {
        closeModal(FILE_UPLOAD_MODAL_ID);
    }

    return {
        init,
        open,
        close,
        triggerFileInput,
        deleteSelected,
        returnSelected,
    };
})();

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("fum-file-input")) {
        FileUploadManager.init();
    }
});

window.FileUploadManager = FileUploadManager;
window.openFileUploadModal = FileUploadManager.open;
window.closeFileUploadModal = FileUploadManager.close;
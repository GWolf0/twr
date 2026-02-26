/**
 * FileUploadManager
 * Handles:
 * - Fetching user files
 * - Uploading
 * - Selecting
 * - Deleting
 * - Returning selected files
 */

const FILE_UPLOAD_MODAL_ID = "fileUploadModal";

const FileUploadManager = (function () {

    let files = [];              // All user files
    let selected = new Set();    // Selected file IDs
    let onSelectCallback = null; // Optional callback when choosing files
    let options = { accept: "file", multiple: true };

    const gridEl = () => document.getElementById("fum-files-grid");
    const fileInputEl = () => document.getElementById("fum-file-input");
    const usageTextEl = () => document.getElementById("fum-usage-text");
    const usageProgressEl = () => document.getElementById("fum-usage-progress");

    /* --------------------------
       INIT
    --------------------------- */
    function init() {
        fileInputEl().addEventListener("change", handleFileInputChange);
        fetchFiles();
    }

    /* --------------------------
       FETCH FILES
    --------------------------- */
    async function fetchFiles() {
        const res = await fetch("/api/v1/file-upload", {
            headers: {
                "ACCEPT": "application/json",
                "AUTHORIZATION": "Bearer " + document
                    .querySelector('meta[name="api-token"]')
                    .getAttribute("content"),
            }
        });
        const data = await res.json();

        files = data.files || [];
        renderFiles();
        updateUsage(data.usage || {});
    }

    /* --------------------------
       RENDER
    --------------------------- */
    function renderFiles() {
        const grid = gridEl();
        grid.innerHTML = "";

        files.forEach(file => {
            const item = document.createElement("div");
            item.className =
                "relative border rounded-lg overflow-hidden cursor-pointer group";

            item.dataset.id = file.id;

            item.innerHTML = `
                <img src="${file.url}"
                     class="w-full h-28 object-cover" />

                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>

                <div class="absolute top-2 right-2 hidden group-hover:block">
                    <div class="w-4 h-4 border-2 border-white rounded"></div>
                </div>
            `;

            item.addEventListener("click", () => toggleSelect(file.id, item));

            grid.appendChild(item);
        });
    }

    /* --------------------------
       SELECT / DESELECT
    --------------------------- */
    function toggleSelect(id, el) {
        if (selected.has(id)) {
            selected.delete(id);
            el.classList.remove("ring-2", "ring-primary");
        } else {
            selected.add(id);
            el.classList.add("ring-2", "ring-primary");
        }
    }

    /* --------------------------
       UPLOAD
    --------------------------- */
    function triggerFileInput() {
        fileInputEl().click();
    }

    async function handleFileInputChange(e) {
        const filesToUpload = e.target.files;
        if (!filesToUpload.length) return;

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
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "AUTHORIZATION": "Bearer " + document
                        .querySelector('meta[name="api-token"]')
                        .getAttribute("content"),
                },
            });
            if (!res.ok) {
                alert(`[FILE_UPLOAD_MANAGER]: error uploading file(s)!`);
            }
            const json = await res.json();
            console.log("json", json);
        } catch (e) {
            console.warn("[FILE_UPLOAD_MANAGER]: " + e);
        }

        fileInputEl().value = "";
        fetchFiles();
    }

    /* --------------------------
       DELETE
    --------------------------- */
    async function deleteSelected() {
        if (!selected.size) return;

        const ids = Array.from(selected);

        await fetch(`/api/v1/file-upload/${ids.join(",")}`, {
            method: "DELETE",
            headers: {
                "ACCEPT": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "AUTHORIZATION": "Bearer " + document
                    .querySelector('meta[name="api-token"]')
                    .getAttribute("content"),
            },
        });

        selected.clear();
        fetchFiles();
    }

    /* --------------------------
       RETURN SELECTED FILES
    --------------------------- */
    function returnSelected() {
        if (onSelectCallback) {
            const selectedFiles = files.filter(f => selected.has(f.id));
            onSelectCallback(selectedFiles);
        }

        closeFileUploadModal();
        selected.clear();
    }

    /* --------------------------
       USAGE PROGRESS
    --------------------------- */
    function updateUsage(usage) {
        if (!usage.total || !usage.used) return;

        const percent = Math.round((usage.used / usage.total) * 100);

        usageTextEl().innerText =
            `${formatBytes(usage.used)} / ${formatBytes(usage.total)}`;

        usageProgressEl()
            .querySelector("div")
            .style.width = percent + "%";
    }

    function formatBytes(bytes) {
        const sizes = ["Bytes", "KB", "MB", "GB"];
        if (bytes === 0) return "0 Byte";
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (
            Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i]
        );
    }

    /* --------------------------
       OPEN MODAL
    --------------------------- */
    function open(callback = null, _options = { accept: "file", multiple: true }) {
        onSelectCallback = callback;
        options = _options;
        document.getElementById("fum-file-input").accept = _options.accept;
        document.getElementById("fum-file-input").multiple = _options.multiple;
        selected.clear();
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

/**
 * Init on load
 */
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("fum-file-input")) FileUploadManager.init();
});

/* --------------------------
   GLOBAL HELPERS
--------------------------- */
window.FileUploadManager = FileUploadManager;
window.openFileUploadModal = FileUploadManager.open;
window.closeFileUploadModal = FileUploadManager.close;

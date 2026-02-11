let openModalsCount = 0;

function getContainer() {
    return document.getElementById("modals-container");
}

function getBackdrop() {
    return document.querySelector('[data-role="modal-backdrop"]');
}

function openModal(id) {
    const modal = document.getElementById(id);
    const container = getContainer();
    const backdrop = getBackdrop();

    if (!modal || !container) return;

    modal.classList.remove("hidden");
    container.classList.remove("hidden");
    container.classList.add("flex");

    requestAnimationFrame(() => {
        modal.classList.remove("opacity-0", "scale-95");
        modal.classList.add("opacity-100", "scale-100");

        backdrop.classList.remove("opacity-0");
        backdrop.classList.add("opacity-100");
    });

    document.body.classList.add("overflow-hidden");

    openModalsCount++;
}

function closeModal(id) {
    const modal = document.getElementById(id);
    const backdrop = getBackdrop();
    const container = getContainer();

    if (!modal) return;

    modal.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
        modal.classList.add("hidden");
    }, 200);

    openModalsCount--;

    if (openModalsCount <= 0) {
        backdrop.classList.remove("opacity-100");
        backdrop.classList.add("opacity-0");

        setTimeout(() => {
            container.classList.add("hidden");
            container.classList.remove("flex");
            document.body.classList.remove("overflow-hidden");
            openModalsCount = 0;
        }, 200);
    }
}

// Close when clicking backdrop
document.addEventListener("click", function (e) {
    const backdrop = getBackdrop();
    if (e.target === backdrop) {
        closeAllModals();
    }
});

// Close on ESC
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeAllModals();
    }
});

function closeAllModals() {
    const modals = document.querySelectorAll('[data-role="modal"]:not(.hidden)');
    modals.forEach(modal => {
        closeModal(modal.id);
    });
}

// Expose globally
window.openModal = openModal;
window.closeModal = closeModal;

const TOAST_DURATION = 4000;

function getToastContainer() {
    return document.getElementById("toasts-container");
}

function toast(message, severity = "info", icon = null) {
    const container = getToastContainer();
    if (!container) return;

    const toastEl = document.createElement("div");

    const variants = {
        info: "bg-secondary text-secondary-foreground border-border",
        success: "bg-accent text-accent-foreground border-border",
        warning: "bg-muted text-foreground border-border",
        error: "bg-destructive text-destructive-foreground border-destructive",
    };

    const icons = {
        info: "bi bi-info-circle",
        success: "bi bi-check-circle",
        warning: "bi bi-exclamation-triangle",
        error: "bi bi-x-circle",
    };

    const chosenIcon = icon ?? icons[severity] ?? null;

    toastEl.className = `
        pointer-events-auto w-full rounded-md border p-4 shadow-lg
        flex items-start gap-3 text-sm
        opacity-0 translate-y-4 scale-95
        transition-all duration-200 ease-out
        ${variants[severity] ?? variants.info}
    `;

    toastEl.innerHTML = `
        ${chosenIcon ? `<i class="${chosenIcon} text-base mt-0.5"></i>` : ""}
        <div class="flex-1">${message}</div>
    `;

    container.appendChild(toastEl);

    // Trigger animation
    requestAnimationFrame(() => {
        toastEl.classList.remove("opacity-0", "translate-y-4", "scale-95");
        toastEl.classList.add("opacity-100", "translate-y-0", "scale-100");
    });

    // Auto remove
    setTimeout(() => {
        closeToast(toastEl);
    }, TOAST_DURATION);
}

function closeToast(toastEl) {
    toastEl.classList.add("opacity-0", "translate-y-4", "scale-95");

    setTimeout(() => {
        toastEl.remove();
    }, 200);
}

// Expose globally
window.toast = toast;

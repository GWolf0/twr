// managers/alertManager.js

function initAlerts() {
    const alerts = document.querySelectorAll('[data-role="alert"]');

    alerts.forEach(alert => {

        // Close button
        const closeBtn = alert.querySelector('[data-role="alert-close"]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeAlert(alert));
        }

        // Autoclose
        if (alert.dataset.autoclose === "true") {
            setTimeout(() => closeAlert(alert), 4000);
        }

    });
}

function closeAlert(alert) {
    alert.style.opacity = "0";
    alert.style.transform = "translateY(-4px)";

    setTimeout(() => {
        alert.remove();
    }, 300);
}

// Run when DOM is ready
document.addEventListener("DOMContentLoaded", initAlerts);

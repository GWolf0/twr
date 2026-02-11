function initDropdowns() {
    const dropdowns = document.querySelectorAll('[data-role="dropdown"]');

    dropdowns.forEach(dropdown => {

        const trigger = dropdown.querySelector('[data-role="dropdown-trigger"]');
        const content = dropdown.querySelector('[data-role="dropdown-content"]');

        if (!trigger || !content) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(content);
        });

        // Prevent closing when clicking inside
        content.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Close when clicking outside
    document.addEventListener('click', closeAllDropdowns);

    // Close on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape") {
            closeAllDropdowns();
        }
    });
}

function toggleDropdown(content) {
    const isOpen = !content.classList.contains('hidden');

    closeAllDropdowns();

    if (!isOpen) {
        openDropdown(content);
    }
}

function openDropdown(content) {
    content.classList.remove('hidden');

    requestAnimationFrame(() => {
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    });
}

function closeAllDropdowns() {
    const openMenus = document.querySelectorAll('[data-role="dropdown-content"]:not(.hidden)');

    openMenus.forEach(menu => {
        menu.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            menu.classList.add('hidden');
        }, 150);
    });
}

document.addEventListener('DOMContentLoaded', initDropdowns);

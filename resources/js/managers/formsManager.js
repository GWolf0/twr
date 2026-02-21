/**
 * Disable form submit buttons while request is processing.
 * Ignores forms marked with data-ajax attribute.
 * Shows confirmation dialog for forms marked with data-confirm.
 */
document.addEventListener('submit', function (e) {

    if (!(e.target instanceof HTMLFormElement)) return;

    const form = e.target;

    // Ignore ajax forms
    if (form.dataset.ajax !== undefined) return;

    // Confirmation handling
    if (form.dataset.confirm !== undefined) {
        const message = form.dataset.confirm || 'Are you sure?';

        if (!window.confirm(message)) {
            e.preventDefault(); // Stop submission
            return;
        }
    }

    const buttons = form.querySelectorAll('button[type="submit"]');

    buttons.forEach(btn => {
        // Prevent double handling
        if (btn.disabled) return;

        btn.disabled = true;

        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = btn.dataset.loading ?? 'Loading...';
    });

});

/**
 * Disable form submit buttons while request is processing.
 * Ignores forms marked with data-ajax attribute.
 */
document.addEventListener('submit', function (e) {

    if (!(e.target instanceof HTMLFormElement)) return;

    const form = e.target;

    // Ignore ajax forms
    if (form.dataset.ajax !== undefined) return;

    const buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

    buttons.forEach(btn => {
        // Prevent double handling
        if (btn.disabled) return;

        btn.disabled = true;
        // classes normally handled in html/blade
        // btn.classList.add('opacity-50', 'cursor-not-allowed');

        if (btn.tagName === 'BUTTON') {
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML = 'Loading...';
        }

        if (btn.tagName === 'INPUT') {
            btn.dataset.originalText = btn.value;
            btn.value = 'Loading...';
        }

    });

});

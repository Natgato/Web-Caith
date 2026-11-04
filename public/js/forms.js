document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirm && ! window.confirm(form.dataset.confirm)) { event.preventDefault(); return; }
        const button = form.querySelector('[data-submit]');
        if (button) { button.dataset.originalLabel = button.textContent; button.disabled = true; button.textContent = 'Enviando…'; }
    });
});
window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-original-label]').forEach((button) => { button.disabled = false; button.textContent = button.dataset.originalLabel; });
});

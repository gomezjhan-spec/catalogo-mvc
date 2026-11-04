document.addEventListener('DOMContentLoaded', () => {
    const errorSummary = document.querySelector('[data-error-summary]');
    if (errorSummary) {
        errorSummary.focus();
    }

    const skuInput = document.querySelector('#sku');
    if (skuInput) {
        skuInput.addEventListener('input', () => {
            const start = skuInput.selectionStart;
            const end = skuInput.selectionEnd;
            skuInput.value = skuInput.value.toUpperCase();
            if (start !== null && end !== null) {
                skuInput.setSelectionRange(start, end);
            }
        });
    }

    document.querySelectorAll('[data-counter]').forEach((counter) => {
        const field = document.getElementById(counter.dataset.counter);
        if (!field) return;

        const updateCount = () => {
            const length = Array.from(field.value).length;
            counter.textContent = `${length} / ${field.maxLength}`;
            counter.classList.toggle('count-near-limit', length >= field.maxLength - 30);
        };
        field.addEventListener('input', updateCount);
        updateCount();
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('.product-form').forEach((form) => {
        form.addEventListener('submit', () => {
            if (!form.checkValidity()) return;
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('is-loading');
                submitButton.setAttribute('aria-busy', 'true');
                submitButton.textContent = 'Guardando…';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form[data-warn-unsaved]');
    if (forms.length === 0) {
        return;
    }

    let hasUnsavedChanges = false;

    forms.forEach((form) => {
        const markUnsaved = (event) => {
            if (event.isTrusted) {
                hasUnsavedChanges = true;
            }
        };

        form.addEventListener('input', markUnsaved);
        form.addEventListener('change', markUnsaved);
        form.addEventListener('submit', () => {
            hasUnsavedChanges = false;
        });
    });

    window.addEventListener('beforeunload', (event) => {
        if (!hasUnsavedChanges) {
            return;
        }
        event.preventDefault();
        event.returnValue = '';
    });
});

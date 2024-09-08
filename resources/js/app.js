import './bootstrap';

import.meta.glob([
    '../images/**',
]);

var clipboard = new ClipboardJS('.copy-to-clipboard');
clipboard.on('error', function (e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});

window.submitDeletionForm = function (event) {
    event.preventDefault();

    const form = event.target.closest('form');

    if (form) {
        if (confirm('Are you sure you want to delete this?')) {
            form.submit();
        }
    } else {
        console.error('No form found');
    }
};

import './bootstrap';

import.meta.glob([
    '../images/**',
]);

var clipboard = new ClipboardJS('.copy-to-clipboard');
clipboard.on('error', function (e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});

window.submitClosestForm = function (event) {
    // Prevent the default action of the event
    event.preventDefault();

    // Find the closest form to the clicked element
    const form = event.target.closest('form');

    // If a form is found, submit it
    if (form) {
        form.submit();
    } else {
        console.error('No form found');
    }
}

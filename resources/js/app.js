import './bootstrap';

import.meta.glob([
    '../images/**',
]);

var clipboard = new ClipboardJS('.copy-to-clipboard');
clipboard.on('error', function (e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});

window.confirmFormSubmit = function (event) {
    event.preventDefault();

    const form = event.target.closest('form');
    if (form) {
        const message = form.dataset.confirmMessage || I18n.trans('phrase.are-you-sure');
        if (confirm(message)) {
            form.submit();
        }
    } else {
        console.error('No form found');
    }
};

window.showPageAlert = function (type, message) {
    const container = document.getElementById('page-alerts');
    if (!container) {
        return;
    }

    const alertElement = document.createElement('div');
    alertElement.className = 'alert alert-' + type + ' fade show';
    alertElement.setAttribute('role', 'alert');
    alertElement.textContent = message;

    container.appendChild(alertElement);
    alertElement.scrollIntoView({behavior: 'smooth', block: 'center'});
};

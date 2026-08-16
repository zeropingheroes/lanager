document.addEventListener("DOMContentLoaded", function () {
    const previewButton = document.getElementById('discord-notification-preview-button');
    if (!previewButton) {
        return;
    }

    const previewResult = document.getElementById('discord-notification-preview-result');
    const messageInput = document.getElementById('message');
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    previewButton.addEventListener('click', function () {
        previewResult.textContent = '';
        previewResult.classList.remove('text-success', 'text-danger');

        fetch(previewButton.dataset.previewUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({content: messageInput.value}),
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return {ok: response.ok, data: data};
                });
            })
            .then(function ({ok, data}) {
                previewResult.classList.add(ok ? 'text-success' : 'text-danger');
                previewResult.textContent = ok
                    ? (data.message || I18n.trans('phrase.event-discord-notification-preview-sent'))
                    : ((data.errors && data.errors[0]) || I18n.trans('phrase.event-discord-notification-preview-failed'));
            })
            .catch(function () {
                previewResult.classList.add('text-danger');
                previewResult.textContent = I18n.trans('phrase.event-discord-notification-preview-failed');
            });
    });
});

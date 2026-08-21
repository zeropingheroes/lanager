window.previewEventDiscordNotificationMessage = function (event) {
    event.preventDefault();

    const link = event.currentTarget;
    if (link.classList.contains('disabled')) {
        return;
    }

    fetch(link.dataset.previewUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            message: link.dataset.content,
            image_paths: link.dataset.imagePaths ? JSON.parse(link.dataset.imagePaths) : [],
        }),
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return {ok: response.ok, data: data};
            });
        })
        .then(function ({ok, data}) {
            showPageAlert(ok ? 'success' : 'danger', ok
                ? (data.message || I18n.trans('phrase.event-discord-notification-preview-sent'))
                : ((data.errors && data.errors[0]) || I18n.trans('phrase.event-discord-notification-preview-failed')));
        })
        .catch(function () {
            showPageAlert('danger', I18n.trans('phrase.event-discord-notification-preview-failed'));
        });
};

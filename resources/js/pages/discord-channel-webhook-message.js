function sendDiscordChannelWebhookMessage(url, message) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({message: message}),
    })
        .then(function (response) {
            return response.json().then(function (data) {
                return {ok: response.ok, data: data};
            });
        });
}

window.sendDiscordChannelWebhookTestMessage = function (event) {
    const button = event.currentTarget;

    if (!confirm(I18n.trans('phrase.are-you-sure'))) {
        return;
    }

    sendDiscordChannelWebhookMessage(button.dataset.sendUrl, button.dataset.message)
        .then(function ({ok, data}) {
            showPageAlert(ok ? 'success' : 'danger', ok
                ? data.message
                : ((data.errors && data.errors[0]) || I18n.trans('phrase.failed-to-send-discord-message')));
        })
        .catch(function () {
            showPageAlert('danger', I18n.trans('phrase.failed-to-send-discord-message'));
        });
};

document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('discord-channel-webhook-message-submit');
    if (!submitButton) {
        return;
    }

    const form = submitButton.closest('form');
    const messageInput = document.getElementById('message');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        sendDiscordChannelWebhookMessage(form.action, messageInput.value)
            .then(function ({ok, data}) {
                showPageAlert(ok ? 'success' : 'danger', ok
                    ? data.message
                    : ((data.errors && data.errors[0]) || I18n.trans('phrase.failed-to-send-discord-message')));
                if (ok) {
                    messageInput.value = '';
                }
            })
            .catch(function () {
                showPageAlert('danger', I18n.trans('phrase.failed-to-send-discord-message'));
            });
    });
});

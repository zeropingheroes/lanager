import {createApp} from 'vue';
import DiscordNotificationMessageImageSelector from '../components/event-discord-notification-messages/image-selector.vue';
import {i18nVue} from 'laravel-vue-i18n';

const selectorApp = document.getElementById('discord-image-selector-app');
if (selectorApp) {
    const app = createApp({});
    app.use(i18nVue, {
        resolve: async lang => {
            const langs = import.meta.glob('../../lang/*.json');
            return await langs[`../../lang/php_${lang}.json`]();
        }
    });
    app.component('DiscordNotificationMessageImageSelector', DiscordNotificationMessageImageSelector);
    app.mount(selectorApp);
}

document.addEventListener('DOMContentLoaded', function () {
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

        const imagePathInputs = document.querySelectorAll('#discord-image-selector-app input[name="image_paths[]"]');
        const imagePaths = Array.from(imagePathInputs).map(function (input) { return input.value; });

        fetch(previewButton.dataset.previewUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({message: messageInput.value, image_paths: imagePaths}),
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

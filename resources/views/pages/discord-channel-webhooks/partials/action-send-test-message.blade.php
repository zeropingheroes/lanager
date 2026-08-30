<button type="button"
        class="btn btn-sm btn-warning"
        data-send-url="{{
            route(
                'api.v1.lans.discord-channel-webhooks.messages.store',
                [
                    'lan' => $lan,
                    'discordChannelWebhook' => $webhook
                ]
            )
        }}"
        data-message="@lang(
            'phrase.discord-test-message',
            [
                'purpose' => trans('title.' . $webhook->purpose),
                'lan' => $lan->name
            ]
        )"
        onclick="sendDiscordChannelWebhookTestMessage(event)"
>
    <i class="fa-solid fa-vial"></i> @lang('title.send-test-message')
</button>

<a href="{{
      route(
          'lans.discord-channel-webhooks.messages.create',
          [
              'lan' => $lan,
              'discord_channel_webhook' => $webhook
          ]
      )
    }}"
   class="btn btn-sm btn-primary"
>
    <i class="fa-solid fa-pen"></i> @lang('title.compose')
</a>

<form method="POST"
      action="{{
        route(
          'lans.discord-channel-webhooks.messages.store',
          [
            'lan' => $lan,
            'discord_channel_webhook' => $webhook
          ]
        )
    }}"
      class="d-inline"
>
    @csrf
    <input type="hidden"
           name="content"
           value="@lang(
               'phrase.discord-test-message',
               [
                   'purpose' => trans('title.' . $webhook->purpose),
                   'lan' => $lan->name
               ]
           )"
    >
    <button type="submit" class="btn btn-sm btn-outline-warning" onclick="confirmFormSubmit(event)">
        <i class="fa-solid fa-vial"></i> @lang('title.send-test-message')
    </button>
</form>

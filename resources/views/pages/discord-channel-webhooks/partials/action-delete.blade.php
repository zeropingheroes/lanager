<form method="POST"
      action="{{
        route(
            'lans.discord-channel-webhooks.destroy',
            [
                'lan' => $lan,
                'discord_channel_webhook' => $webhook
            ]
        )
        }}"
      class="d-inline"
>
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="confirmFormSubmit(event)">
        <i class="fa-solid fa-trash"></i> @lang('title.delete')
    </button>
</form>

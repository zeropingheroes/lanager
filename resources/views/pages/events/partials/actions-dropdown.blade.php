@component('components.actions-dropdown')
    <li><h6 class="dropdown-header">@lang('title.event')</h6></li>
    @can('update', $event)
        <a class="dropdown-item copy-to-clipboard"
           href="#"
           data-clipboard-text="[{{ $event->name }}]({{ route('lans.events.show', ['lan' => $event->lan, 'event' => $event], false) }})">
            <i class="fa-solid fa-copy"></i> @lang('title.copy-markdown-link')
        </a>
        <a href="{{ route('lans.events.edit', ['lan' => $event->lan, 'event' => $event->id]) }}" class="dropdown-item">
            <i class="fa-solid fa-pen-to-square"></i> @lang('title.edit-item', ['item' => __('title.event')])
        </a>
    @endcan
    @can('delete', $event)
        <form action="{{ route('lans.events.destroy', ['lan' => $event->lan, 'event' => $event->id]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);">
                <i class="fa-solid fa-trash"></i> @lang('title.delete-item', ['item' => __('title.event')])
            </a>
        </form>
    @endcan
    @can('update', \Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage::class)
        <li><h6 class="dropdown-header">@lang('title.discord')</h6></li>
        @if($event->discordNotificationMessage)
            <a href="{{ route('lans.events.discord-notification-message.edit', ['lan' => $lan, 'event' => $event]) }}" class="dropdown-item">
                <i class="fa-solid fa-pen-to-square"></i> @lang('title.edit-item', ['item' => __('title.notification-message')])
            </a>

            @php
                $testWebhookConfigured = $lan->discordChannelWebhooks->contains('purpose', 'test');
            @endphp
            <a class="dropdown-item {{ $testWebhookConfigured ? '' : 'disabled' }}"
               href="#"
               data-preview-url="{{ route('lans.discord-notification-message.preview', ['lan' => $lan]) }}"
               data-content="{{ $event->discordNotificationMessage->message }}"
               onclick="previewEventDiscordNotificationMessage(event);"
               @if(! $testWebhookConfigured) title="@lang('phrase.no-test-webhook-configured')" @endif
            >
                <i class="fa-solid fa-eye"></i> @lang('title.preview-in-test-channel')
            </a>

            @php
                $liveWebhookConfigured = $lan->discordChannelWebhooks->contains('purpose', 'live');
            @endphp
            <form method="POST"
                  action="{{ route('lans.events.discord-notification-message.send', ['lan' => $lan, 'event' => $event]) }}"
                  data-confirm-message="@lang('phrase.confirm-send-event-discord-notification-message-now')"
            >
                @csrf
                <a class="dropdown-item {{ $liveWebhookConfigured ? '' : 'disabled' }}"
                   href="#"
                   onclick="confirmFormSubmit(event);"
                   @if(! $liveWebhookConfigured) title="@lang('phrase.no-live-webhook-configured')" @endif
                >
                    <i class="fa-solid fa-paper-plane"></i> @lang('title.send-now')
                </a>
            </form>

            <form action="{{ route('lans.events.discord-notification-message.destroy', ['lan' => $lan, 'event' => $event]) }}"
                  method="POST"
                  data-confirm-message="@lang('phrase.are-you-sure-delete')"
            >
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <a class="dropdown-item" href="#" onclick="confirmFormSubmit(event);">
                    <i class="fa-solid fa-trash"></i> @lang('title.delete-item', ['item' => __('title.notification-message')])
                </a>
            </form>
        @else
            <a href="{{ route('lans.events.discord-notification-message.create', ['lan' => $lan, 'event' => $event]) }}" class="dropdown-item">
                <i class="fa-solid fa-plus"></i> @lang('title.create-item', ['item' => __('title.notification-message')])
            </a>
        @endif
    @endcan
@endcomponent

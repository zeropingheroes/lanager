@php
    $testWebhookConfigured = $lan->discordChannelWebhooks->contains('purpose', 'test');
@endphp

<div class="row mb-3">
    <label for="message" class="col-sm-2 col-form-label">@lang('title.message')</label>
    <div class="col-sm-10">
        <textarea id="message"
                  name="message"
                  class="form-control font-monospace"
                  rows="8"
                  maxlength="2000"
                  placeholder="@lang('phrase.event-discord-notification-message-placeholder')"
        >{{ old('message', $notificationMessage ?? '') }}</textarea>
        <div class="form-text text-muted">
            {!! trans('phrase.discord-message-content-help', ['url' => trans('phrase.discord-markdown-help-link-url')]) !!}
        </div>
        <div class="form-text text-muted">
            @lang('phrase.discord-message-mentions-help')
        </div>
        <div class="form-text text-muted">
            {!! trans('phrase.event-discord-notification-message-variables-help') !!}
        </div>
    </div>
</div>

@include('pages.event-discord-notification-messages.partials.image-selector', [
    'availableImages' => $availableImages,
    'selectedImages' => $selectedImages
])

<div class="row mb-3">
    <label class="col-sm-2 col-form-label pt-0">@lang('title.send-automatically')</label>
    <div class="col-sm-10">
        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   id="automatic"
                   name="automatic"
                   value="1"
                   {{ old('automatic', $automaticDefault ?? true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="automatic">
                @lang('phrase.automatically-send-discord-message-at-event-start-time')
            </label>
        </div>
        <div class="form-text text-muted">
            @lang('phrase.regardless-send-discord-message-any-time')
        </div>
    </div>
</div>

@can('preview', \Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage::class)
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label pt-0">@lang('title.preview')</label>
        <div class="col-sm-10">
            <button type="button"
                    id="discord-notification-preview-button"
                    class="btn btn-sm btn-outline-warning"
                    data-preview-url="{{ route('lans.events.discord-notification-message.preview', ['lan' => $lan, 'event' => $event]) }}"
                    @disabled(! $testWebhookConfigured)
                    @if(! $testWebhookConfigured) title="@lang('phrase.no-test-webhook-configured')" @endif
            >
                <i class="fa-solid fa-eye"></i> @lang('title.preview-in-test-channel')
            </button>
            <span id="discord-notification-preview-result" class="ms-2 small"></span>
        </div>
    </div>
@endcan

<div class="row mb-3">
    <div class="offset-sm-2 d-grid col-sm-10 gap-2">
        <button type="submit" class="btn btn-primary">@lang('title.submit')</button>
    </div>
</div>

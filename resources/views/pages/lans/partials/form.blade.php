@vite('resources/js/pages/lan-form.js')
@include('components.form.inputs.name', ['value' => $lan->name, 'required' => true])
@include('components.form.inputs.start-end', ['start' => $lan->start, 'end' => $lan->end, 'required' => true])
<div class="row mb-3">
    @include('components.form.label', ['for' => 'venue_id', 'text' => __('title.venue')])
    <div class="col-sm-10">
        @include('components.form.select', [
            'name' => 'venue_id',
            'item' => $lan,
            'items' => $venues,
            'labelField' => 'name',
            'blank' => true,
            ])
    </div>
</div>
<div class="row mb-3">
    @include('components.form.label', ['for' => 'achievement_id', 'text' => __('title.lan-achievement')])
    <div class="col-sm-10">
        @include('components.form.select',[
            'name' => 'achievement_id',
            'item' => $lan,
            'items' => $achievements,
            'labelField' => 'name',
            'blank' => true,
        ])
        <small id="achievement_id_help"
               class="form-text"
        >
            @lang('phrase.lan-achievement-help')
        </small>
    </div>
</div>
@include('components.form.inputs.published', ['value' => $lan->published])
<div class="row mb-3">
    @include('components.form.label', ['for' => 'default_event_discord_notification_message', 'text' => __('title.default-event-discord-notification-message')])
    <div class="col-sm-10">
        <textarea id="default_event_discord_notification_message"
                  name="default_event_discord_notification_message"
                  class="form-control font-monospace"
                  rows="4"
                  maxlength="2000"
                  placeholder="@lang('phrase.default-event-discord-notification-message')"
        >{{ old(
            'default_event_discord_notification_message',
            $lan->default_event_discord_notification_message ?? trans('phrase.default-event-discord-notification-message')
        ) }}</textarea>
        <div class="form-text text-muted">
            @lang('phrase.default-event-discord-notification-message-help')
        </div>
        <div class="form-text text-muted">
            @lang('phrase.discord-message-content-help', ['url' => trans('phrase.discord-markdown-help-link-url')])
        </div>
        <div class="form-text text-muted">
            @lang('phrase.discord-message-mentions-help')
        </div>
        <div class="form-text text-muted">
            @lang('phrase.event-discord-notification-message-variables-help')
        </div>
    </div>
</div>
@include('components.form.required-legend')
@include('components.form.inputs.submit')

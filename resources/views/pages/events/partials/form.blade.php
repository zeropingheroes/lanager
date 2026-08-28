@vite('resources/js/pages/event-form.js')
@include('components.form.inputs.name', ['value' => $event->name, 'required' => true])
@include('components.form.inputs.description', ['value' => $event->description])
@include('components.form.inputs.start-end', ['start' => $event->start, 'end' => $event->end, 'required' => true])

<div class="row mb-3">
    @include('components.form.label', ['for' => 'signups_open', 'text' => __('title.signups-open')])
    <div class="col-sm-4">
        <input type="text"
               class="form-control datetimepicker-input"
               id="signups_open"
               name="signups_open"
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('signups_open', $event->signups_open) }}"
               data-toggle="datetimepicker"
               data-target="#signups_open"
        >
    </div>
    @include('components.form.label', ['for' => 'signups_close', 'text' => __('title.signups-close')])
    <div class="col-sm-4">
        <input type="text"
               class="form-control datetimepicker-input"
               id="signups_close"
               name="signups_close"
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('signups_close', $event->signups_close) }}"
               data-toggle="datetimepicker"
               data-target="#signups_close"
        >
    </div>
</div>

@include('components.form.inputs.published', ['value' => $event->published])

@can('update', \Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage::class)
    @unless($event->exists)
        <div class="row mb-3">
            @include('components.form.label', [
                'for' => 'create_default_discord_notification_message',
                'text' => __('title.create-default-discord-notification-message'),
                'class' => 'col-sm-2 col-form-label pt-0',
            ])
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           id="create_default_discord_notification_message"
                           name="create_default_discord_notification_message"
                           value="1"
                           {{ old('create_default_discord_notification_message', true) ? 'checked' : null }}
                    >
                </div>
                <div class="form-text text-muted">
                    @lang('phrase.create-default-discord-notification-message-help')
                </div>
            </div>
        </div>
    @endunless
@endcan

@include('components.form.required-legend')
@include('components.form.inputs.submit')

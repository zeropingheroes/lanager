@vite('resources/js/pages/event-form.js')
@include('components.form.inputs.name', ['value' => $event->name])
@include('components.form.inputs.description', ['value' => $event->description])

@if($lan->discord_webhook_url)
<div class="row mb-3">
    <label for="discord_notify"
           class="col-sm-2 col-form-label"
    >
        @lang('title.discord-notify')
    </label>
    <div class="col-sm-10">
        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   id="discord_notify"
                   name="discord_notify"
                   value="1"
                   {{ old('discord_notify', $event->discord_notify) ? 'checked' : null }}
            >
        </div>
        <small class="form-text text-muted">
            @lang('phrase.discord-notify-help')
        </small>
    </div>
</div>

<div class="row mb-3" id="discord_message_row" style="{{ old('discord_notify', $event->discord_notify) ? '' : 'display:none' }}">
    <label for="discord_message"
           class="col-sm-2 col-form-label"
    >
        @lang('title.discord-message')
    </label>
    <div class="col-sm-10">
        <textarea class="form-control"
                  id="discord_message"
                  name="discord_message"
                  rows="5"
                  aria-describedby="discord_message_help"
        >{{ old('discord_message', $event->discord_message) }}</textarea>
        <small id="discord_message_help"
               class="form-text text-muted"
        >
            @lang('phrase.discord-message-help')
        </small>
    </div>
</div>
@endif

@include('components.form.inputs.start-end', ['start' => $event->start, 'end' => $event->end])

<div class="row mb-3">
    <label for="signups_open"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-open')
    </label>
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
    <label for="signups_close"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-close')
    </label>
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
@include('components.form.inputs.submit')

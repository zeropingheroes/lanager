@extends('layouts.default')

@section('title')
    @lang('title.clone-item', ['item' => __('title.event')])
@endsection

@section('content-header')
    <h1>@lang('title.clone-item', ['item' => __('title.event')])</h1>
    {{ Breadcrumbs::render('lans.events.clone.create', $lan, $event) }}
@endsection

@section('content')
    @vite(['resources/js/pages/event-form.js', 'resources/js/pages/lan-item-clone-form.js'])

    @include('components.form.create', ['route' => route('lans.events.clone.store', ['lan' => $lan, 'event' => $event])])

    <div class="row mb-3">
        @include('components.form.label', ['for' => 'lan_id', 'text' => __('title.clone-to-lan'), 'required' => true])
        <div class="col-sm-10">
            @include('components.form.select', [
                'name' => 'lan_id',
                'item' => $event,
                'items' => $lans,
                'labelField' => 'name',
            ])
        </div>
    </div>

    <script type="application/json" id="lans-data">{!! $lans->map(fn ($destinationLan) => [
        'id' => $destinationLan->id,
        'start' => $destinationLan->start->toIso8601String(),
        'end' => $destinationLan->end->toIso8601String(),
    ])->toJson() !!}</script>

    @include('components.form.inputs.name', ['value' => $event->name, 'required' => true])
    @include('components.form.inputs.description', ['value' => $event->description])
    @include('components.form.inputs.start-end', ['start' => $event->start, 'end' => $event->end, 'required' => true])

    <div class="row mb-3">
        <div class="offset-sm-2 col-sm-10">
            <span id="clone-form-out-of-range-warning" class="text-warning" hidden>
                <i class="fa-solid fa-triangle-exclamation"></i> @lang('phrase.out-of-time-range')
            </span>
        </div>
    </div>

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

    @php
        $hasSourceMessage = $event->discordNotificationMessage !== null;
        $defaultMessageOption = $hasSourceMessage ? 'clone_existing' : 'create_default';
    @endphp
    <div class="row mb-3">
        @include('components.form.label', [
            'text' => __('title.event-discord-notification-message'),
            'class' => 'col-sm-2 col-form-label pt-0',
        ])
        <div class="col-sm-10">
            <div class="form-check">
                <input type="radio"
                       class="form-check-input"
                       id="discord_notification_message_option_clone_existing"
                       name="discord_notification_message_option"
                       value="clone_existing"
                    {{ old('discord_notification_message_option', $defaultMessageOption) === 'clone_existing' ? 'checked' : null }}
                    {{ ! $hasSourceMessage ? 'disabled' : null }}
                >
                <label class="form-check-label" for="discord_notification_message_option_clone_existing">
                    @lang('title.clone-existing-discord-notification-message')
                </label>
                @unless($hasSourceMessage)
                    <span id="discord_notification_message_option_clone_existing_unavailable"
                          class="text-secondary ms-2">
                        <i class="fa-solid fa-circle-info"></i> @lang('phrase.source-event-has-no-message')
                    </span>
                @endunless
            </div>
            <div class="form-check">
                <input type="radio"
                       class="form-check-input"
                       id="discord_notification_message_option_create_default"
                       name="discord_notification_message_option"
                       value="create_default"
                    {{ old('discord_notification_message_option', $defaultMessageOption) === 'create_default' ? 'checked' : null }}
                >
                <label class="form-check-label" for="discord_notification_message_option_create_default">
                    @lang('title.create-default-discord-notification-message')
                </label>
            </div>
            <div class="form-check">
                <input type="radio"
                       class="form-check-input"
                       id="discord_notification_message_option_none"
                       name="discord_notification_message_option"
                       value="none"
                    {{ old('discord_notification_message_option', $defaultMessageOption) === 'none' ? 'checked' : null }}
                >
                <label class="form-check-label" for="discord_notification_message_option_none">
                    @lang('title.none')
                </label>
            </div>
        </div>
    </div>

    @include('components.form.required-legend')
    @include('components.form.inputs.submit')
    @include('components.form.close')
@endsection

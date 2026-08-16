@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.event-discord-notification-message')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.event-discord-notification-message')])</h1>
    {{ Breadcrumbs::render('lans.events.discord-notification-message.edit', $lan, $event) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('lans.events.discord-notification-message.update', ['lan' => $lan, 'event' => $event])])
    @include('pages.events.discord-notification-message.partials.form', [
        'lan' => $lan,
        'notificationMessage' => $event->discordNotificationMessage?->message,
        'automaticDefault' => $event->discordNotificationMessage?->automatic ?? true,
    ])
    @include('components.form.close')

    <h4 class="mt-4">@lang('title.event')</h4>
    @include('pages.events.discord-notification-message.partials.event-details', ['event' => $event])
@endsection

@vite('resources/js/pages/event-discord-notification-message-form.js')

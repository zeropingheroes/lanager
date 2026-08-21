@extends('layouts.default')

@section('title')
    @lang('title.create-item', ['item' => __('title.event-discord-notification-message')])
@endsection

@section('content-header')
    <h1>@lang('title.create-item', ['item' => __('title.event-discord-notification-message')])</h1>
    {{ Breadcrumbs::render('lans.events.discord-notification-message.create', $lan, $event) }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.events.discord-notification-message.store', ['lan' => $lan, 'event' => $event])])
    @include('pages.event-discord-notification-messages.partials.form', [
        'lan' => $lan,
        'automaticDefault' => true,
    ])
    @include('components.form.close')

    <h4 class="mt-4">@lang('title.event')</h4>
    @include('pages.event-discord-notification-messages.partials.event-details', ['event' => $event])
@endsection

@vite('resources/js/pages/event-discord-notification-message-form.js')

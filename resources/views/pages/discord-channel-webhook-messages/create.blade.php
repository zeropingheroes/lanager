@extends('layouts.default')

@section('title')
    @lang('title.send-message-to-discord')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.send-message-to-discord')</h1>
        </div>
    </div>
    {{ Breadcrumbs::render('lans.discord-channel-webhooks.messages.create', $lan, $webhook) }}
@endsection

@section('content')

    @include('components.form.create',
      [
        'route' =>
        route(
          'api.v1.lans.discord-channel-webhooks.messages.store',
            [
              'lan' => $lan,
              'discordChannelWebhook' => $webhook
            ]
        )
      ]
    )

    <div class="row mb-3">
        @include('components.form.label', ['text' => __('title.channel')])
        <div class="col-sm-10 d-flex align-items-center">
            <span class="badge fs-6 bg-{{ $webhook->purpose === 'live' ? 'danger' : 'info' }}">
                {{ trans('title.' . $webhook->purpose) }}
            </span>
        </div>
    </div>

    <div class="row mb-3">
        @include('components.form.label', ['for' => 'message', 'text' => __('title.message'), 'required' => true])
        <div class="col-sm-10">
            <textarea id="message"
                      name="message"
                      class="form-control font-monospace"
                      rows="8"
                      maxlength="2000"
                      placeholder="@lang('phrase.discord-message-placeholder')"
            >{{ old('message') }}</textarea>
            <div class="form-text text-muted">
                @lang('phrase.discord-message-content-help', ['url' => trans('phrase.discord-markdown-help-link-url')])
            </div>
            <div class="form-text text-muted">
                @lang('phrase.discord-message-mentions-help')
            </div>
        </div>
    </div>
    @include('components.form.required-legend')
    <div class="row mb-3">
        <div class="offset-sm-2 d-grid col-sm-10 gap-2">
            <button type="submit" id="discord-channel-webhook-message-submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> @lang('title.send')</button>
        </div>
    </div>
    @include('components.form.close')
@endsection

@vite('resources/js/pages/discord-channel-webhook-message.js')

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
          'lans.discord-channel-webhooks.messages.store',
            [
              'lan' => $lan,
              'discord_channel_webhook' => $webhook
            ]
        )
      ]
    )

    <div class="row mb-3">
        <label class="col-sm-2 col-form-label">@lang('title.channel')</label>
        <div class="col-sm-10 d-flex align-items-center">
            <span class="badge fs-6 bg-{{ $webhook->purpose === 'live' ? 'danger' : 'info' }}">
                {{ trans('title.' . $webhook->purpose) }}
            </span>
        </div>
    </div>

    <div class="row mb-3">
        <label for="content" class="col-sm-2 col-form-label">@lang('title.message')</label>
        <div class="col-sm-10">
            <textarea id="content"
                      name="content"
                      class="form-control font-monospace"
                      rows="8"
                      maxlength="2000"
                      placeholder="@lang('phrase.discord-message-placeholder')"
            >{{ old('content') }}</textarea>
            <div class="form-text text-muted">
                {!! trans(
                  'phrase.discord-message-content-help',
                  ['url' => trans('phrase.discord-markdown-help-link-url')]
                ) !!}
            </div>
            <div class="form-text text-muted">
                @lang('phrase.discord-message-mentions-help')
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="offset-sm-2 d-grid col-sm-10 gap-2">
            <button type="submit" class="btn btn-primary">@lang('title.send')</button>
        </div>
    </div>
    @include('components.form.close')
@endsection

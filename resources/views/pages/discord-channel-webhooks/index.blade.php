@extends('layouts.default')

@section('title')
    @lang('title.discord-channel-webhooks')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')

    @can('index', \Zeropingheroes\Lanager\Models\DiscordChannelWebhook::class)

        <table class="table table-striped align-middle mb-4">
            <thead>
            <tr>
                <th>@lang('title.purpose')</th>
                <th>@lang('title.webhook-url')</th>
                <th>@lang('title.actions')</th>
            </tr>
            </thead>
            <tbody>
            @if($lan->discordChannelWebhooks->isEmpty())
                <tr>
                    <td colspan="3">
                        @lang('phrase.no-discord-channel-webhooks-yet')
                    </td>
                </tr>
            @else
                @foreach($webhooks as $webhook)
                    <tr>
                        <td>
                                <span class="badge bg-{{ $webhook->purpose === 'live' ? 'danger' : 'info' }}">
                                    {{ trans('title.' . $webhook->purpose) }}
                                </span>
                        </td>
                        <td>
                            <code>{{ substr($webhook->webhook_url, 0, 40) }}...</code>
                        </td>
                        <td>
                            @include('pages.discord-channel-webhooks.partials.action-compose')
                            @include('pages.discord-channel-webhooks.partials.action-send-test-message')
                            @include('pages.discord-channel-webhooks.partials.action-delete')
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        @include('pages.discord-channel-webhooks.partials.form')
    @endcan
@endsection

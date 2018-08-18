@extends('layouts.default')

@section('title')
    {{ $lan->name }}
@endsection

@section('content')

    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1>
                {{ $lan->name }}
                <small class="text-muted">
                    @include('pages.lans.partials.dates', ['lan' => $lan])
                </small>
            </h1>
        </div>
        @canany(['update', 'delete'], $lan)
        <div class="col text-right">
            @include('pages.lans.partials.actions-dropdown', ['lan' => $lan])
        </div>
        @endcanany
    </div>
    <div class="row">
        <div class="col">
            <h4>
                @include('pages.lans.partials.timespan', ['lan' => $lan])
                <small class="text-muted">
                    @include('pages.lans.partials.duration', ['lan' => $lan])
                </small>
            </h4>
        </div>
    </div>

    {{ Breadcrumbs::render('lans.show', $lan) }}
    @include('components.alerts.all')

    @if($lan->description)
        {!! Markdown::convertToHtml($lan->description) !!}
    @endif

    <div class="row">
        <div class="col-auto">
            <h5>@lang('title.events')</h5>
        </div>
        @can('create', \Zeropingheroes\Lanager\Event::class)
            <div class="col text-right">
                <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary btn-sm" title="@lang('title.create')">
                    <span class="oi oi-plus"></span>
                </a>
            </div>
        @endcan
    </div>
    @if(! $lan->events->isEmpty())
        @include('pages.events.partials.list', ['events' => $lan->events])
    @endif

    <div class="row">
        <div class="col-auto">
            <h5>@lang('title.guides')</h5>
        </div>
        @can('create', \Zeropingheroes\Lanager\Guide::class)
            <div class="col text-right">
                <a href="{{ route( 'lans.guides.create', $lan) }}" class="btn btn-primary btn-sm" title="@lang('title.create')">
                    <span class="oi oi-plus"></span>
                </a>
            </div>
        @endcan
    </div>
    @if(! $lan->guides->isEmpty())
        @include('pages.guides.partials.list', ['guides' => $lan->guides])
    @endif

    @if( ! $lan->users->isEmpty())
        <h5>{{ $lan->users->count() }} @lang('title.attendees')</h5>
        @include('pages.users.partials.list', ['users' => $lan->users])
    @endif

    @include('components.buttons.edit', ['item' => $lan])
    @include('components.buttons.delete', ['item' => $lan])

@endsection
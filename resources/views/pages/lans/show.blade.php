@extends('layouts.default')

@section('title')
    {{ $lan->name }}
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col-md-auto">
            <h1 class="mb-0">
                {{ $lan->name }}
            </h1>
        </div>
        @canany(['update', 'delete'], $lan)
        <div class="col text-right">
            @include('pages.lans.partials.actions-dropdown', ['lan' => $lan])
        </div>
        @endcanany
    </div>
    @if( $lan->venue )
        <h5><a href="{{ route('venues.show', $lan->venue) }}">{{ $lan->venue->name }}</a></h5>
    @endif
    {{ Breadcrumbs::render('lans.show', $lan) }}
@endsection

@section('content-alerts')
    @parent
    @canany(['update', 'delete'], $lan)
        @if(!$lan->published)
            @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.item-unpublished', ['item' => __('title.lan')])])
        @endif
    @endcanany
@endsection

@section('content')
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

    @if( ! $games->isEmpty())
        <h5>@lang('title.popular-games')</h5>
        <table class="table table-striped popular-games">
            @foreach($games as $game)
                <tr>
                    <td class="game">
                        @include('pages.games.partials.game-image-link', ['game' => $game['game']])
                    </td>
                    <td class="playtime">
                        {{ $game['playtime']->seconds(0)->cascade()->forHumans() }}
                    </td>
                    <td class="players">
                        @foreach($game['users'] as $user)
                            <a href="{{ route('users.show', $user->id) }}">
                                @include('pages.users.partials.avatar', ['user' => $user])
                            </a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    @if( ! $lan->users->isEmpty())
        <h5>{{ $lan->users->count() }} @lang('title.attendees')</h5>
        @include('pages.users.partials.list', ['users' => $lan->users])
    @endif

@endsection
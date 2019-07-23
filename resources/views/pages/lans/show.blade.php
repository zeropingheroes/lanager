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


    <ul class="nav nav-tabs" id="lanTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="events-tab" data-toggle="tab" href="#events" role="tab" aria-controls="events" aria-selected="true">
                @lang('title.events') <span class="badge">{{ $lan->events->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="guides-tab" data-toggle="tab" href="#guides" role="tab" aria-controls="guides" aria-selected="false">
                @lang('title.guides') <span class="badge">{{ $lan->guides->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="popular-games-tab" data-toggle="tab" href="#popular-games" role="tab" aria-controls="popular-games" aria-selected="false">
                @lang('title.popular-games') <span class="badge">{{ $games->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="attendees-tab" data-toggle="tab" href="#attendees" role="tab" aria-controls="attendees" aria-selected="false">
                @lang('title.attendees') <span class="badge">{{ $lan->users->count() }}</span>
            </a>
        </li>
        @can('create', \Zeropingheroes\Lanager\Slide::class)
            <li class="nav-item">
                <a class="nav-link" id="slides-tab" data-toggle="tab" href="#slides" role="tab" aria-controls="slides" aria-selected="false">
                    @lang('title.slides') <span class="badge">{{ $lan->slides->count() }}</span>
                </a>
            </li>
        @endcan
    </ul>
    <div class="tab-content" id="lanTabsContent">
        <div class="tab-pane fade show active" id="events" role="tabpanel" aria-labelledby="events-tab">
            @if(! $lan->events->isEmpty())
                @include('pages.events.partials.list', ['events' => $lan->events])
            @endif
            @can('create', \Zeropingheroes\Lanager\Event::class)
                <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary btn-sm mt-2" title="@lang('title.create')">
                    @lang('title.create')
                </a>
            @endcan
        </div>
        <div class="tab-pane fade" id="guides" role="tabpanel" aria-labelledby="guides-tab">
            @if(! $lan->guides->isEmpty())
                @include('pages.guides.partials.list', ['guides' => $lan->guides])
            @endif
            @can('create', \Zeropingheroes\Lanager\Guide::class)
                <a href="{{ route( 'lans.guides.create', $lan) }}" class="btn btn-primary btn-sm mt-2" title="@lang('title.create')">
                    @lang('title.create')
                </a>
            @endcan
        </div>
        <div class="tab-pane fade" id="popular-games" role="tabpanel" aria-labelledby="popular-games-tab">
            @include('pages.lans.partials.popular-games', ['games' => $games])
        </div>
        <div class="tab-pane fade" id="attendees" role="tabpanel" aria-labelledby="attendees-tab">
            @include('pages.users.partials.list', ['users' => $lan->users])
        </div>
        @can('create', \Zeropingheroes\Lanager\Slide::class)
            <div class="tab-pane fade" id="slides" role="tabpanel" aria-labelledby="slides-tab">
                @if(! $lan->slides->isEmpty())
                    @include('pages.slides.partials.list', ['slides' => $lan->slides])
                @endif
                <a href="{{ route( 'lans.slides.create', $lan) }}" class="btn btn-primary btn-sm mt-2" title="@lang('title.create')">
                    @lang('title.create')
                </a>
                <a href="{{ route( 'lans.slides.play', $lan) }}" class="btn btn-primary btn-sm mt-2" title="@lang('title.play')" target="_blank">
                    @lang('title.play')
                </a>
            </div>
        @endcan
    </div>


@endsection
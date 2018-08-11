@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    @include('pages.events.partials.index-header', ['active' => 'list', 'lan' => $lan])
    {{ Breadcrumbs::render('lans.events.index', $lan) }}
    @include('components.alerts.all')

    @if( empty($events))
        <p>@lang('phrase.no-items-found', ['item' => __('title.events')])</p>
    @else
        @include('pages.events.partials.list', ['events' => $events])
        @can('create', Zeropingheroes\Lanager\Event::class)
            <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary">@lang('title.create')</a>
        @endcan
    @endif
@endsection
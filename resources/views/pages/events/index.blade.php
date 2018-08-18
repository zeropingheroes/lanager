@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content-header')
    @include('pages.events.partials.header-index', ['active' => 'list', 'lan' => $lan])
    {{ Breadcrumbs::render('lans.events.index', $lan) }}
@endsection

@section('content')
    @include('pages.events.partials.list', ['events' => $events])
    @can('create', Zeropingheroes\Lanager\Event::class)
        <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary">@lang('title.create')</a>
    @endcan
@endsection
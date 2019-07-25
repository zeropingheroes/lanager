@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content-header')
    @include('pages.lans.partials.header', ['lan', $lan])
@endsection

@section('content')
    @include('pages.events.partials.list', ['events' => $events])
    @can('create', Zeropingheroes\Lanager\Event::class)
        <a href="{{ route( 'lans.events.create', $lan) }}" class="btn btn-primary">@lang('title.create')</a>
    @endcan
@endsection
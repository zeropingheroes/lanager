@extends('layouts.default')

@section('title')
    @lang('title.events')
@endsection

@section('content')
    <h1>@lang('title.events')</h1>
    @include('components.alerts.all')

    @if( empty($events))
        <p>@lang('phrase.no-items-found', ['item' => __('title.events')])</p>
    @else
        @include('pages.events.partials.list', ['events' => $events])
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\Event::class])
    @endif
@endsection
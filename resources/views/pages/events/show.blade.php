@extends('layouts.default')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    @include('pages.events.partials.header', ['event' => $event])
    @include('pages.events.partials.time-info', ['event' => $event])
    {{ Breadcrumbs::render('lans.events.show', $lan, $event) }}
    @include('components.alerts.all')

    @canany(['update', 'delete'], $event)
    @if(!$event->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.resource-not-published', ['resource' => strtolower(__('title.event'))])])
    @endif
    @endcanany

    {!! Markdown::convertToHtml($event->description) !!}

    <hr>

    @can('update', $event)
        <a href="{{ route( 'lans.events.edit', ['lan' => $lan, 'event' => $event]) }}" class="btn btn-primary">@lang('title.edit')</a>
    @endcan
    @can('delete', $event)
        <form action="{{ route( 'lans.events.destroy', ['lan' => $lan, 'event' => $event]) }}" method="POST" class="inline">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger">@lang('title.delete')</button>
        </form>
    @endcan

@endsection
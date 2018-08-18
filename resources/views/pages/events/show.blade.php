@extends('layouts.default')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    @include('pages.events.partials.header-show', ['event' => $event])
    {{ Breadcrumbs::render('lans.events.show', $lan, $event) }}
    @include('components.alerts.all')

    @canany(['update', 'delete'], $event)
    @if(!$event->published)
        @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.resource-not-published', ['resource' => strtolower(__('title.event'))])])
    @endif
    @endcanany

    {!! Markdown::convertToHtml($event->description) !!}

@endsection
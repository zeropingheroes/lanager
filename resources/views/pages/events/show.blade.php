@extends('layouts.default')

@section('title')
    {{ $event->name }}
@endsection

@section('content-header')
    @include('pages.events.partials.header-show', ['event' => $event])
    {{ Breadcrumbs::render('lans.events.show', $lan, $event) }}
@endsection

@section('content')
    @canany(['update', 'delete'], $event)
        @if(!$event->published)
            @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.resource-not-published', ['resource' => strtolower(__('title.event'))])])
        @endif
    @endcanany
    @canany(['update', 'delete'], $event->lan)
        @if(!$event->lan->published)
            @include('components.alerts.alert-single', ['type' => 'warning', 'message' => __('phrase.resource-not-published', ['resource' => __('title.lan')])])
        @endif
    @endcanany

    {!! Markdown::convertToHtml($event->description) !!}

@endsection
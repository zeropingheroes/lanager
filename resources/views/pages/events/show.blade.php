@extends('layouts.default')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    @include('pages.events.partials.header', ['event' => $event])
    @include('components.alerts.all')

    @include('pages.events.partials.time-info', ['event' => $event])

    {!! Markdown::convertToHtml($event->description) !!}

    <hr>

    @include('components.buttons.edit', ['item' => $event])
    @include('components.buttons.delete', ['item' => $event])

@endsection
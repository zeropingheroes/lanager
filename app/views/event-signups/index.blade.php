@extends('layouts.default')
@section('content')

    @include('events.partials.header')
    @include('events.partials.time-info')

    @include('event-signups.partials.title')
    @include('layouts.default.alerts')

    @include('event-signups.partials.list')

    @include('event-signups.partials.signup-button')

@endsection

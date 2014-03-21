@extends('lanager-core::layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($event, array('route' => 'event.store', 'event' => $event->id)) }}

@include('lanager-core::event.form')

{{ Form::close() }}
@endsection
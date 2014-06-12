@extends('layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($event, array('route' => 'events.store', 'event' => $event->id)) }}

@include('events.form')

{{ Form::close() }}

@endsection
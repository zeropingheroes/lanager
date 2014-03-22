@extends('layouts.default')
@section('content')

<h3>{{ $title }}</h3>

{{ Form::model($event, array('route' => 'event.store', 'event' => $event->id)) }}

@include('event.form')

{{ Form::close() }}
@endsection
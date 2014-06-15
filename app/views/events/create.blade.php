@extends('layouts.default')
@section('content')
	{{ Form::model($event, array('route' => 'events.store', 'event' => $event->id)) }}
		@include('events.form')
	{{ Form::close() }}
@endsection

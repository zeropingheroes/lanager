@extends('layouts.default')
@section('content')
	{{ Form::model($event, array('route' => 'events.store', 'event' => $event->id, 'class' => 'event-create')) }}
		@include('events.form')
	{{ Form::close() }}
@endsection

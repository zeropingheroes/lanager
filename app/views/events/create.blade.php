@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($event, array('route' => 'events.store', 'event' => $event->id, 'class' => 'event-create')) }}
		@include('events.partials.form')
	{{ Form::close() }}
@endsection

@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($eventType, array('route' => array('event-types.update', $eventType->id), 'method' => 'PUT')) }}
		@include('event-types.partials.form')
	{{ Form::close() }}
	{{ HTML::button('event-types.destroy', $eventType->id) }}
@endsection

@extends('layouts.default')
@section('content')
	{{ Form::model($event, array('route' => array('events.update', $event->id), 'method' => 'PUT')) }}
		@include('events.form')
	{{ Form::close() }}
@endsection

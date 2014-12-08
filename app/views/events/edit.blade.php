@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'PUT'] ) }}
		@include('events.partials.form')
	{{ Form::close() }}
@endsection

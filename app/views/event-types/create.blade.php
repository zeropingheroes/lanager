@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($eventType, ['route' => 'event-types.store'] ) }}
		@include('event-types.partials.form')
	{{ Form::close() }}
@endsection

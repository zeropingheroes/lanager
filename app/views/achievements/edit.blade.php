@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($achievement, array('route' => array('achievements.update', $achievement->id), 'method' => 'PUT')) }}
		@include('achievements.partials.form')
	{{ Form::close() }}
@endsection
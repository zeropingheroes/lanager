@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($achievement, array('route' => 'achievements.store')) }}
		@include('achievements.partials.form')
	{{ Form::close() }}
@endsection
@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($achievement, ['route' => 'achievements.store', 'class' => 'form-horizontal']) }}
		@include('achievements.partials.form')
	{{ Form::close() }}
@endsection
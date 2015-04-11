@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($userAchievement, ['route' => ['user-achievements.update', $userAchievement->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
		@include('user-achievements.partials.form')
	{{ Form::close() }}

@endsection

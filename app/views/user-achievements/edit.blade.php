@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($userAchievement, ['route' => ['user-achievements.update', $userAchievement->id], 'method' => 'PUT']) }}
		@include('user-achievements.partials.form')
	{{ Form::close() }}

@endsection

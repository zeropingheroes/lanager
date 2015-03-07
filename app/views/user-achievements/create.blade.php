@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($userAchievement, ['route' => 'user-achievements.store']) }}
		@include('user-achievements.partials.form')
	{{ Form::close() }}

@endsection

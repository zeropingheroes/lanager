@extends('layouts.default')
@section('content')
	{{ Form::model($achievement, array('route' => 'achievements.store', 'achievement' => $achievement->id)) }}
		@include('achievements.form')
	{{ Form::close() }}
@endsection
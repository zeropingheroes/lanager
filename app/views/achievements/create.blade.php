@extends('layouts.default')
@section('content')
	{{ Form::model($achievement, array('route' => 'achievements.store', 'achievement' => $achievement->id)) }}
		@include('achievements.partials.form')
	{{ Form::close() }}
@endsection
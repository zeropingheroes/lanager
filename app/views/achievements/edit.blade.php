@extends('layouts.default')
@section('content')
	{{ Form::model($achievement, array('route' => array('achievements.update', $achievement->id), 'method' => 'PUT')) }}
		@include('achievements.form')
	{{ Form::close() }}
@endsection
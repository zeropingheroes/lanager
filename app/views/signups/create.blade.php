@extends('layouts.default')
@section('content')
	{{ Form::model($signup, array('route' => 'signups.store', 'signup' => $signup->id)) }}
		@include('signups.form')
	{{ Form::close() }}
@endsection

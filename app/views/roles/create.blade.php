@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($role, array('route' => 'roles.store')) }}
		@include('roles.partials.form')
	{{ Form::close() }}
@endsection

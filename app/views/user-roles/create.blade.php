@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($userRole, array('route' => 'user-roles.store')) }}
		@include('user-roles.partials.form')
	{{ Form::close() }}
@endsection

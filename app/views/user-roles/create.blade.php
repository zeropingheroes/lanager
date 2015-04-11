@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($userRole, ['route' => 'user-roles.store', 'class' => 'form-horizontal']) }}
		@include('user-roles.partials.form')
	{{ Form::close() }}

@endsection

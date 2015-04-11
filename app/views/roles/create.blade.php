@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($role, ['route' => 'roles.store', 'class' => 'form-horizontal']) }}
		@include('roles.partials.form')
	{{ Form::close() }}

@endsection

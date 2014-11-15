@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($roleAssignment, array('route' => 'role-assignments.store', 'role-assignment' => $roleAssignment->id)) }}
		@include('roleassignments.partials.form')
	{{ Form::close() }}
@endsection

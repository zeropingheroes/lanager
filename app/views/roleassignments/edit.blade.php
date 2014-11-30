@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($roleAssignment, array('route' => array('role-assignments.update', $roleAssignment->id), 'method' => 'PUT')) }}
		@include('roleassignments.partials.form')
	{{ Form::close() }}
@endsection

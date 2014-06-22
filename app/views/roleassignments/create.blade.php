@extends('layouts.default')
@section('content')
	{{ Form::model($roleAssignment, array('route' => 'role-assignments.store', 'role-assignment' => $roleAssignment->id)) }}
		@include('roleassignments.form')
	{{ Form::close() }}
@endsection

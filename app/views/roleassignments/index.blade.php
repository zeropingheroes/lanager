@extends('layouts.default')
@section('content')
	@include('roleassignments.list')
	{{ HTML::button('role-assignments.create') }}
@endsection

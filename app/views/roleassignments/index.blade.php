@extends('layouts.default')
@section('content')
	@include('roleassignments.list')
	{{ link_to_route('role-assignments.create', 'Create') }}
@endsection

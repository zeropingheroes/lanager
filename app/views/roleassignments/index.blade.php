@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@include('roleassignments.partials.list')
	{{ HTML::button('role-assignments.create') }}
@endsection

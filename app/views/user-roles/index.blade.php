@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@include('user-roles.partials.list')
	{{ HTML::button('user-roles.create') }}
@endsection

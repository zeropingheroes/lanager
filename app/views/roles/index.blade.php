@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<ul>
		@include('roles.partials.list')
	</ul>
	{{ HTML::button('roles.create') }}
@endsection

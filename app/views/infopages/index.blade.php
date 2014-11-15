@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<ul>
		@include('infopages.partials.list')
	</ul>
	{{ HTML::button('infopages.create') }}
@endsection

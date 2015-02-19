@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<ul>
		@include('event-types.partials.list')
	</ul>
	{{ HTML::button('event-types.create') }}
@endsection

@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<ul>
		@include('pages.partials.list')
	</ul>
	{{ HTML::button('pages.create') }}
@endsection

@extends('layouts.default')
@section('content')
	<ul>
		@include('infopages.list')
	</ul>
	{{ HTML::button('infopages.create') }}
@endsection

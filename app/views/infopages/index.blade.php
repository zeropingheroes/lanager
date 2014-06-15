@extends('layouts.default')
@section('content')
	<ul>
		@include('infopages.list')
	</ul>
	{{ HTML::resourceCreate('infopages', 'Create') }}
@endsection

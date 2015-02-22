@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@include('user-achievements.partials.list')
	<br>
	{{ HTML::button('user-achievements.create') }}
@endsection

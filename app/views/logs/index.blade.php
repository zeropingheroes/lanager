@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@include('logs.partials.filters')
	@include('logs.partials.list')

@endsection

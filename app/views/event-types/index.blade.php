@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@include('event-types.partials.list')

	@include('buttons.create', ['resource' => 'event-types'])
@endsection

@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@include('event-signups.partials.list')
	@include('event-signups.partials.signup-button', ['event' => $event])
@endsection

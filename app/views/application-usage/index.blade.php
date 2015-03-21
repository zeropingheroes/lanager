@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@include('application-usage.partials.list', ['applications' => $applications] )

@endsection

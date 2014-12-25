@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($lan, array('route' => 'lans.store')) }}
		@include('lans.partials.form')
	{{ Form::close() }}
@endsection

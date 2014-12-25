@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($lan, array('route' => array('lans.update', $lan->id), 'method' => 'PUT')) }}
		@include('lans.partials.form')
	{{ Form::close() }}
@endsection

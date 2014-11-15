@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($infoPage, array('route' => array('infopages.update', $infoPage->id), 'method' => 'PUT')) }}
		@include('infopages.partials.form')
	{{ Form::close() }}
@endsection

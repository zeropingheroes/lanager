@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($infoPage, array('route' => 'infopages.store', 'info' => $infoPage->id)) }}
		@include('infopages.partials.form')
	{{ Form::close() }}
@endsection

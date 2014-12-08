@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($page, array('route' => array('pages.update', $page->id), 'method' => 'PUT')) }}
		@include('pages.partials.form')
	{{ Form::close() }}
@endsection

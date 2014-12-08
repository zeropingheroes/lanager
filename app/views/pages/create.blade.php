@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($page, array('route' => 'pages.store')) }}
		@include('pages.partials.form')
	{{ Form::close() }}
@endsection

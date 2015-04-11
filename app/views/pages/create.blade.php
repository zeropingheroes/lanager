@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($page, ['route' => 'pages.store', 'class' => 'form-horizontal']) }}
		@include('pages.partials.form')
	{{ Form::close() }}

@endsection

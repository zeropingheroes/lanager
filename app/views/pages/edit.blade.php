@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($page, ['route' => ['pages.update', $page->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
		@include('pages.partials.form')
	{{ Form::close() }}

@endsection

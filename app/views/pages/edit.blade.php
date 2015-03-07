@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($page, ['route' => ['pages.update', $page->id], 'method' => 'PUT']) }}
		@include('pages.partials.form')
	{{ Form::close() }}

@endsection

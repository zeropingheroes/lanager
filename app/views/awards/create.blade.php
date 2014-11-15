@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($award, array('route' => 'awards.store', 'award' => $award->id)) }}
		@include('awards.partials.form')
	{{ Form::close() }}
@endsection

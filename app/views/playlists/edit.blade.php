@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	{{ Form::model($playlist, array('route' => array('playlists.update', $playlist->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
		@include('playlists.partials.form')
	{{ Form::close() }}
@endsection

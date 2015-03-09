@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	{{ Form::model($playlist, ['route' => 'playlists.store', 'class' => 'form-horizontal'] ) }}
		@include('playlists.partials.form')
	{{ Form::close() }}

@endsection

@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<p>{{ $playlist->description }}</p>
	@include('playlist-items.partials.form')
	@include('playlist-items.partials.list')
	<br>
@endsection

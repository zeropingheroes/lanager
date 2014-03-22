@extends('layouts.default')
@section('content')
	<h2>{{{ $title }}}</h2>
	@include('playlists.items.form')
	@include('playlists.items.list')
@endsection
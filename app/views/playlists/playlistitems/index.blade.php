@extends('layouts.default')
@section('content')

	<div class="row">
		<div class="col-md-6">
			<h2>{{{ $title }}} </h2>
		</div>
		<div class="col-md-6">
			<div class="pull-right playlist-controls">@include('playlists.controls')</div>
		</div>
	</div>
	@include('playlists.playlistitems.form')
	@include('playlists.playlistitems.list')
@endsection
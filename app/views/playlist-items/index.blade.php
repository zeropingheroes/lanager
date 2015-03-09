@extends('layouts.default')
@section('content')

	@include('playlists.partials.header', ['playlist' => $playlist])
	@include('layouts.default.alerts')

	@include('playlist-items.partials.form', ['playlist' => $playlist])

	@include('playlist-items.partials.list', ['items' => $playlistItems])

@endsection

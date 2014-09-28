@extends('layouts.default')
@section('content')
	<p>{{ $playlist->description }}</p>
	@include('playlists.items.form')
	@include('playlists.items.list')
	<br>
	@if( ! $history )
		{{ link_to_route('playlists.items.index', 'History', array('playlist' => $playlist->id, 'history' => 1 ) ) }}
	@else
		{{ link_to_route('playlists.items.index', 'Queue', array('playlist' => $playlist->id ) ) }}
	@endif
@endsection

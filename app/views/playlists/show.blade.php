@extends('layouts.default')
@section('content')

	@include('playlists.partials.header', ['playlist' => $playlist])

	@include('layouts.default.alerts')
	<p>
		{{ link_to_route('playlists.items.index',  $playlist->playlistItems()->count() . ' items', $playlist->id) }}
	</p>

	@include('buttons.edit', ['resource' => 'playlists', 'item' => $playlist])
	@include('buttons.destroy', ['resource' => 'playlists', 'item' => $playlist])
@endsection				

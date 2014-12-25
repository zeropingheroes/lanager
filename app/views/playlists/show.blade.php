@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	<p>{{ $playlist->description }}</p>
<p>Items: {{ $playlist->playlistItems()->count() }}</p>

	{{ HTML::button('playlists.create') }}
	{{ HTML::button('playlists.edit', $playlist->id) }}
	{{ HTML::button('playlists.destroy', $playlist->id) }}
@endsection				

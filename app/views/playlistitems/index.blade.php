@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	<p>{{ $playlist->description }}</p>
	@include('playlistitems.partials.form')
	@include('playlistitems.partials.list')
	<br>
	@if( ! $history )
		{{ link_to_route('playlists.items.index', 'History', array('playlist' => $playlist->id, 'history' => 1 ) ) }}
	@else
		{{ link_to_route('playlists.items.index', 'Queue', array('playlist' => $playlist->id ) ) }}
	@endif
@endsection

@extends('layouts.default')
@section('content')

	@include('playlists.partials.header', ['playlist' => $playlist])
	@include('layouts.default.alerts')

	@include('playlist-items.partials.form', ['playlist' => $playlist])

	{{
		Navigation::tabs([
		[
			'title' => 'Unplayed' . View::make('badge', ['collection' => $unplayedItems] ),
			'link' => route('playlists.items.index', ['playlist' => $playlist->id, 'tab' => 'unplayed'] ),
			'active' => (Input::get('tab') == 'unplayed' OR empty(Input::get('tab')) ),
		],
		[
			'title' => 'Played' . View::make('badge', ['collection' => $playedItems] ),
			'link' => route('playlists.items.index', ['playlist' => $playlist->id, 'tab' => 'played'] ),
			'active' => Input::get('tab') == 'played',
		],
		[
			'title' => 'Skipped' . View::make('badge', ['collection' => $skippedItems] ),
			'link' => route('playlists.items.index', ['playlist' => $playlist->id, 'tab' => 'skipped'] ),
			'active' => Input::get('tab') == 'skipped',
		],
		])
	}}

	@if( Input::get('tab') == 'unplayed' OR empty(Input::get('tab')) )

		@include('playlist-items.partials.list-unplayed', ['items' => $unplayedItems])

	@elseif( Input::get('tab') == 'played' )

		@include('playlist-items.partials.list-played', ['items' => $playedItems])

	@elseif( Input::get('tab') == 'skipped' )

		@include('playlist-items.partials.list-skipped', ['items' => $skippedItems])

	@endif



@endsection

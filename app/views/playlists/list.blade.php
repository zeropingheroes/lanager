@if(!empty($playlists))
	@foreach($playlists as $playlist)
		<li>{{ link_to_route('playlists.items.index', $playlist->name, $playlist->id) }}</li>
	@endforeach
@endif

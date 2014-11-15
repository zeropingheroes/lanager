@if(!empty($playlists))
	@foreach($playlists as $playlist)
		<li>{{ link_to_route('playlistitems.index', $playlist->name, $playlist->id) }}</li>
	@endforeach
@endif

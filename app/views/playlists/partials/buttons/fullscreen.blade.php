@if( $playlist->count() )
	@include('buttons.url',
	[
		'url' => URL::route('playlists.play', $playlist->id),
		'icon' => 'fullscreen',
		'hover' => 'Open the playback page for this playlist',
		'target' => '_blank',
		'size' => 'extraSmall',
	])
@endif
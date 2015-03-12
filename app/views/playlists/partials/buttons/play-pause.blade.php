@include('buttons.update',
[
	'resource' => 'playlists',
	'item' => $playlist,
	'size' => 'extraSmall',
	'icon' => ( $playlist->playback_state == 0 ? 'play' : 'pause' ),
	'hover' => ( $playlist->playback_state == 0 ? 'Unpause this playlist' : 'Pause this playlist' ),
	'data' =>
		[
			'playback_state' => ( $playlist->playback_state == 0 ? 1 : 0 ),
		],
])

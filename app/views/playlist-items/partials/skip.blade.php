@include(
	'buttons.update',
	[
		'resource' => 'playlists.items',
		'icon' => 'stepForward',
		'hover' => 'Skip this item',
		'size' => 'extraSmall',
		'data' => 
		[
			'playback_state' => 2,
			'skip_reason' => 'Skipped by admin',
		],
		'parameters' =>
		[
			'playlist_id' => $item->playlist_id,
			'item_id' => $item->id,
		],
	])

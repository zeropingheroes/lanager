@include(
	'buttons.destroy',
	[
		'resource' => 'playlists.items',
		'item' => $item,
		'size' => 'extraSmall',
		'parameters' =>
		[
			'playlist_id' => $item->playlist_id,
			'playlist_item_id' => $item->id,
		],
	])

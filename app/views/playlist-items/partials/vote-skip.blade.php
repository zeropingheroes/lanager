@include(
	'buttons.store',
	[
		'resource' => 'playlists.items.votes',
		'icon' => 'removeSign',
		'hover' => 'Vote to skip this item',
		'size' => 'extraSmall',
		'parameters' =>
		[
			'playlist_id' => $item->playlist_id,
			'item_id' => $item->id,
		],
	])
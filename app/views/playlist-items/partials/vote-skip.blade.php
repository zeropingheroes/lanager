@if( in_array( Auth::user()->id, $item->playlistItemVotes->lists('user_id')) )
	<?php
		$playlistItemVote = $item->playlistItemVotes->filter(function($item) {
			return $item->user_id == Auth::user()->id;
		})->first();
	?>
	@include(
		'buttons.destroy',
		[
			'resource' => 'playlists.items.votes',
			'item' => $playlistItemVote,
			'icon' => 'removeSign',
			'hover' => 'Undo vote to skip this playlist item',
			'size' => 'extraSmall',
			'type' => 'danger',
			'parameters' =>
			[
				'playlist_id' => $item->playlist_id,
				'item_id' => $item->id,
				'vote_id' => $playlistItemVote->id,
			],
		])
@else
	@include(
		'buttons.store',
		[
			'resource' => 'playlists.items.votes',
			'icon' => 'removeSign',
			'hover' => 'Vote to skip this playlist item',
			'size' => 'extraSmall',
			'parameters' =>
			[
				'playlist_id' => $item->playlist_id,
				'item_id' => $item->id,
			],
		])
@endif
<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist;
use Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVote;

class PlaylistItemService extends NestedResourceService {

	public $resourceName = 'playlist item';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
		];
		parent::__construct($listener, $models);
	}

}
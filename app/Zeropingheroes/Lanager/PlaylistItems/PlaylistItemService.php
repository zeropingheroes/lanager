<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist;

class PlaylistItemService extends NestedResourceService {

	public $resource = 'playlist item';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
		];
		parent::__construct($listener, $models);
	}

}
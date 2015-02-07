<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseResourceService;

class PlaylistService extends BaseResourceService {

	public $resourceName = 'playlist';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Playlist;
	}

}
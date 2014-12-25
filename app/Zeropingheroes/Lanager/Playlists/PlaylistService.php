<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class PlaylistService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'playlist';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Playlist;
	}

}
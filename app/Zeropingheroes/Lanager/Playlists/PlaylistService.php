<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\SingularResourceService;

class PlaylistService extends SingularResourceService {

	protected $resource = 'playlist';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Playlist);
	}

}
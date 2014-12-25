<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Playlists\PlaylistService,
	Zeropingheroes\Lanager\Playlists\PlaylistTransformer;

class PlaylistsController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistService($this);
		$this->transformer = new PlaylistTransformer;
	}

}
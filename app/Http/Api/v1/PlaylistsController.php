<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistTransformer;

class PlaylistsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistService($this);
		$this->transformer = new PlaylistTransformer;
	}

}
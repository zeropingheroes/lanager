<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class PlaylistsController extends ResourceServiceController {

	use FlatResourceTrait;

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistService;
		$this->transformer = new PlaylistTransformer;
	}

}
<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemService;
use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemTransformer;

class PlaylistItemsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemService($this);
		$this->transformer = new PlaylistItemTransformer;
	}

}
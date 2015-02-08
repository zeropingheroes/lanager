<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\PlaylistItems\PlaylistItemService,
	Zeropingheroes\Lanager\PlaylistItems\PlaylistItemTransformer;

class PlaylistItemsController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemService($this);
		$this->transformer = new PlaylistItemTransformer;
	}

}
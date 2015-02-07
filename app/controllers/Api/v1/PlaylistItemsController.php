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

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// TODO: fix parent Api\v1\BaseController signature to allow for multiple IDs
		$playlistId = 1;
		$items = $this->service->all($playlistId);
		return $this->response->collection($items, $this->transformer);
	}

}
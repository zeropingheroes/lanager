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
		$this->service = new PlaylistItemService;
		$this->transformer = new PlaylistItemTransformer;
	}

	public function index( $playlistId )
	{
		$items = $this->service->filterByPlaylist( $playlistId )->all();

		return $this->response->collection( $items, $this->transformer );
	}

	public function show( $playlistId, $playlistItemId )
	{
		$item = $this->service->filterByPlaylist( $playlistId )->single( $playlistItemId );

		return $this->response->item( $item, $this->transformer );
	}
}
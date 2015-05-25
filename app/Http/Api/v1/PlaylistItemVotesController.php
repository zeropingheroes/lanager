<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteService;
use Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteTransformer;

class PlaylistItemVotesController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemVoteService;
		$this->transformer = new PlaylistItemVoteTransformer;
	}

	public function index( $playlistId, $playlistItemId )
	{
		$items = $this->service->filterByPlaylistItem( $playlistItemId )->all();

		return $this->response->collection( $items, $this->transformer );
	}

	public function show( $playlistId, $playlistItemId, $playlistItemVoteId )
	{
		$item = $this->service->filterByPlaylistItem( $playlistItemId )->single( $playlistItemVoteId );

		return $this->response->item( $item, $this->transformer );
	}
}
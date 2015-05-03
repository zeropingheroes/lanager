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
		$this->service = new PlaylistItemVoteService($this);
		$this->transformer = new PlaylistItemVoteTransformer;
	}

}
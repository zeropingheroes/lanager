<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteService;
use Redirect;

class PlaylistItemVotesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PlaylistItemVoteService;
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('playlists.items.index', $this->currentRouteParameters()['playlists'] );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return $this->redirectAfterStore();
	}

}
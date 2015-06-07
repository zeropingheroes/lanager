<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteService;
use Redirect;
use Input;

class PlaylistItemVotesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PlaylistItemVoteService;
	}

	public function store()
	{
		$input = Input::all();
		$input['playlist_item_id'] = func_get_arg(0);

		return parent::processStore( $input );
	}

	public function destroy()
	{
		$this->service = $this->service->filterByPlaylistItem( func_get_arg(0) );

		return parent::processDestroy( func_get_arg(1) );
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
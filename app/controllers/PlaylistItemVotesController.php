<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVoteService;
use Notification, Redirect;

class PlaylistItemVotesController extends BaseController {

	protected $route = 'playlists.items.votes';

	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemVoteService($this);
	}

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( 'playlists.items.index', $service->resourceIds() );
	}

}
<?php namespace Zeropingheroes\Lanager\Gui;

use Zeropingheroes\Lanager\BaseResourceService;
use Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVoteService;
use Notification, Redirect;

class PlaylistItemVotesController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'playlists.items.votes';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemVoteService($this);
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( 'playlists.items.index', $service->resourceIds() );
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( 'playlists.items.index', $service->resourceIds() );
	}

}
<?php namespace Zeropingheroes\Lanager\Gui;

use Zeropingheroes\Lanager\BaseResourceService;
use Zeropingheroes\Lanager\PlaylistItems\PlaylistItemService;
use View, Notification, Redirect;

class PlaylistItemsController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'playlists.items';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PlaylistItemService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($playlistId)
	{
		$playlist = $this->service->parent([$playlistId]);

		$eagerLoad =
		[
			'playlistItemVotes',
			'user.state.application',
			'user.state.server'
		];
		$unplayedItems = $this->service->all([$playlistId], ['playback_state' => 0], $eagerLoad);
		$playedItems = $this->service->all([$playlistId], ['playback_state' => 1, 'orderBy' => '-updated_at'], $eagerLoad);
		$skippedItems = $this->service->all([$playlistId], ['playback_state' => 2, 'orderBy' => '-updated_at'], $eagerLoad);

		return View::make('playlist-items.index')
					->with('title','Playlist: '. $playlist->name)
					->with('playlist', $playlist)
					->with('playedItems', $playedItems)
					->with('unplayedItems', $unplayedItems)
					->with('skippedItems', $skippedItems);
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

}
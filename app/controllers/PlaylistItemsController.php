<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\PlaylistItems\PlaylistItemService;
use View;

class PlaylistItemsController extends BaseController {

	protected $route = 'playlists.items';

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

		return View::make('playlist-items.index')
					->with('title','Playlist: '. $playlist->name)
					->with('playlist', $playlist)
					->with('playlistItems', $this->service->all([$playlistId]));
	}

}
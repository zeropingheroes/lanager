<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use View;
use Redirect;
use Input;

class PlaylistItemsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PlaylistItemService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( $playlistId )
	{
		$playlist = (new PlaylistService)->single( $playlistId );

		$unplayed 	= (new PlaylistItemService)->filterByPlaylist( $playlistId )->unplayed();
		$played 	= (new PlaylistItemService)->filterByPlaylist( $playlistId )->played();
		$skipped 	= (new PlaylistItemService)->filterByPlaylist( $playlistId )->skipped();

		return View::make('playlist-items.index')
					->with('title','Playlist: '. $playlist->name)
					->with('playlist', $playlist)
					->with('unplayedItems', $unplayed)
					->with('playedItems', $played)
					->with('skippedItems', $skipped);
	}

	public function store()
	{
		$input = Input::all();
		$input['playlist_id'] = func_get_arg(0);

		return parent::processStore( $input );
	}

	public function update()
	{
		$this->service = $this->service->filterByPlaylist( func_get_arg(0) );

		return parent::processUpdate( func_get_arg(1), Input::get() );
	}

	public function destroy()
	{
		$this->service = $this->service->filterByPlaylist( func_get_arg(0) );

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
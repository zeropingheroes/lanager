<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\BaseController;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\PlaylistItems\PlaylistItem,
	Zeropingheroes\Lanager\PlaylistItems\PlayableItemFactory,
	Zeropingheroes\Lanager\PlaylistItems\UnplayableItemException;

use View, Auth, Input, Redirect, Config, Notification;

class PlaylistItemsController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['store', 'update', 'destroy'] ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);

		$playlistItems = $playlist->playlistItems()
					->where('playback_state', 0)
					->orderBy('created_at', 'asc')
					->paginate(10);

		return View::make('playlist-items.index')
					->with('title', 'Playlist - ' . $playlist->name)
					->with('playlist', $playlist)
					->with('playlistItems', $playlistItems);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);

		$playlistItem = new PlaylistItem;
		$playlistItem->playlist_id 	= $playlistId;
		$playlistItem->user_id 		= Auth::user()->id;

		$providers = Config::get('lanager/playlist.providers');

		try // ...to pull in the item (by URL) from a provider
		{
			$playableItem = (new PlayableItemFactory)->create( Input::get('url'), $providers);
		}
		catch(UnplayableItemException $e)
		{
			Notification::danger($e->getMessage());
			return Redirect::back();
		}

		$playlistItem->fill( $playableItem->toArray() );

		if ( ! $this->save($playlistItem) ) return Redirect::back()->withInput();
		
		return Redirect::route('playlists.items.index', $playlist->id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($playlistId, $playlistItemId)
	{
		$playlistItem = Playlist::findOrFail($playlistId)->playlistItems()->findOrFail($playlistItemId);
			
		$playlistItem->fill( Input::get() );

		if ( ! $this->save($playlistItem) ) return Redirect::back()->withInput();
		
		return Redirect::route('playlists.items.index', $playlist->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistId, $playlistItemId)
	{
		$playlistItem = Playlist::findOrFail($playlistId)->playlistItems()->findOrFail($playlistItemId);
		$this->delete($playlistItem);
		return Redirect::back();
	}
}
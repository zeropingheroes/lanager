<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\PlaylistItem;
use View, Response, Auth, Input, Redirect, Request, DB;

class PlaylistItemsController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter( 'checkResourcePermission', array('only' => array('store', 'update', 'destroy', 'current')) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($playlistId)
	{
		// Show all unplayed playlist items for this playlist
		if ( $playlist = Playlist::find($playlistId) )
		{
			$playlistItems = PlaylistItem::where('playlist_id', $playlistId)
				->where('playback_state', 0)
				->orderBy('created_at', 'asc')
				->paginate(10);

			return View::make('playlists.playlistitems.index')
						->with('title', 'Playlist')
						->with('playlist', $playlist)
						->with('playlistItems', $playlistItems);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($playlistId)
	{
		if( Playlist::find($playlistId) )
		{
			$playlistItem = new PlaylistItem;
			$playlistItem->playlist_id = $playlistId;
			$playlistItem->user_id = Auth::user()->id;
			$playlistItem->url = Input::get('url');

			if( ! $playlistItem->save() )
			{
				return Redirect::route('playlists.playlistitems.index', array('playlist' => $playlistId))->withErrors($playlistItem->validationErrors);
			}
			else
			{
				return Redirect::route('playlists.playlistitems.index', array('playlist' => $playlistId));
			}
		}
		else
		{
			App::abort(404, 'Playlist not found');
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($playlistId, $playlistItemId)
	{
		if ( Request::ajax() )
		{
			if( $playlistItem = PlaylistItem::find($playlistItemId) )
			{
				$playlistItem->touch();
				// Return affected rows
				return DB::table('playlist_items')
							->where('id', '=', $playlistItemId)
							->limit(1)
							->update(array('playback_state' => Input::get('playback_state')));
			}
			else
			{
				return 0;
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistItemId)
	{
		// DELETE SINGLE PLAYLIST ITEM
	}

	/**
	 * Display a listing of playlist items that have been played.
	 *
	 * @return Response
	 */
	public function history($playlistId)
	{
		if ( $playlist = Playlist::find($playlistId) )
		{
			$playlistItems = PlaylistItem::where('playlist_id', $playlistId)
				->where('playback_state', '!=', 0)
				->orderBy('updated_at', 'desc')
				->paginate(10);
			
			return View::make('playlists.playlistitems.index')
						->with('title', 'Playlist History')
						->with('playlist', $playlist)
						->with('playlistItems', $playlistItems)
						->with('history', true);
		}
	}

}
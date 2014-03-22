<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\PlaylistItem;
use View, Response, Auth, Input, Redirect, Request, DB;

class PlaylistItemsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($playlistId)
	{
		// SHOW ALL PLAYLIST ITEMS FOR PLAYLIST $ID
		$playlist = Playlist::find($playlistId);
		$playlistItems = PlaylistItem::where('playlist_id', $playlistId)
			->whereIn('playback_state', array(0,1,2))
			->orderBy('created_at', 'asc')
			->paginate(10);
		return View::make('playlists.items.index')
					->with('title', 'Playlist')
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
		$playlistItem = new PlaylistItem;
		$playlistItem->playlist_id = $playlistId;
		$playlistItem->user_id = Auth::user()->id;
		$playlistItem->url = Input::get('url');

		if( ! $playlistItem->save() )
		{
			return Redirect::route('playlists.items.index', array('playlist' => $playlistId))->withErrors($playlistItem->validationErrors);
		}
		else
		{
			return Redirect::route('playlists.items.index', array('playlist' => $playlistId));
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($playlistItemId)
	{
		// SHOW SINGLE PLAYLIST ITEM
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($playlistItemId)
	{
		// EDIT SINGLE PLAYLIST ITEM
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
			// Return affected rows
			return DB::table('playlist_items')
						->where('id', '=', $playlistItemId)
						->limit(1)
						->update(array('playback_state' => Input::get('playback_state')));
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
	 * Retrieve the current item for a given playlist.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function current($playlistId)
	{
		$playlistItem = PlaylistItem::with('user')
			->where('playback_state', '=', 2)		// paused entries
			->orWhere('playback_state', '=', 1)		// playing entries
			->orWhere('playback_state', '=', 0)		// unplayed entries
			->orderBy('playback_state', 'desc')		// precedence order: paused, playing, unplayed
			->orderBy('created_at', 'asc')			// secondary order: oldest to newest
			->first();
		if ( ! empty($playlistItem) ) return Response::json($playlistItem); // return playlist entry as JSON
	}



	/**
	 * Pause playback of the specified playlist item
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function pause($playlistId, $playlistItemId)
	{
		if( $playlistItem = PlaylistItem::find($playlistItemId))
		{
			// Toggle play / pause
			if($playlistItem->playback_state == 1)
			{
				$playlistItem->playback_state = 2;
			}
			elseif( $playlistItem->playback_state == 2)
			{
				$playlistItem->playback_state = 1;
			}
			$playlistItem->save();
		}
		return Redirect::back();
	}
}
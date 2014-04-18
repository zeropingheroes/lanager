<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\PlaylistItem;
use View, Response, Input, Redirect, Request;

class PlaylistsController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter( 'checkResourcePermission', array('only' => array('create', 'store', 'show', 'edit', 'update', 'destroy')) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// List of playlists
		// Show "add new items to this playlist" to logged in users
		// Show "play this playlist on this computer" link to admins
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function show($playlistId)
	{
		// Play through items in the playlist
		if( $playlist = Playlist::find($playlistId) )
		{
			// Javascript call for latest playlist item
			if ( Request::ajax() )
			{
				// Output the current playlist item, playlist item submitter and playlist 
				$playlist = $playlist->playlistItems()
					->where('playback_state', '=', '0')
					->orderBy('created_at', 'asc')
					->with('user')
					->with('playlist')
					->first();
				if ( ! empty($playlist) ) 
				{
					return Response::json($playlist); // return playlist entry as JSON
				}
				else
				{
					return Response::json(false); // TODO: return "no items to play"
				}
			}
			else // View of the screen
			{
				return View::make('playlists.show')
							->with('title', 'Now Playing - ' . $playlist->name . ' Playlist')
							->with('playlist', $playlist);				
			}
		}
		else
		{
			App::abort(404, 'Playlist not found');
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function edit($playlistId)
	{
		// Edit playlist details (excluding the items)
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function update($playlistId)
	{
		// Update playlist details, including pausing/playing
		if ( $playlist = Playlist::find($playlistId) )
		{
			$playlist->name = Input::get('name');
			$playlist->playback_state = Input::get('playback_state');
			
			if ( ! $playlist->save() )
			{
				return Redirect::route('playlists.edit', array('playlist' => $playlist->id))->withErrors($playlist->errors());
			}
			return Redirect::route('playlists.playlistitems.index',array('playlist' => $playlist->id));
		}
		else
		{
			App::abort(404, 'Playlist not found');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function destroy($playlistId)
	{
		//
	}

}
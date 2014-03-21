<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\PlaylistItem;
use View, Response;

class PlaylistController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// LIST OF PLAYLISTS
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// CREATE NEW PLAYLIST FORM
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// SAVE NEW PLAYLIST
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function show($playlistId)
	{
		$playlist = Playlist::find($playlistId);
		return View::make('playlist.show')
					->with('title', 'Playlist')
					->with('playlist', $playlist);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function edit($playlistId)
	{
		// EDIT SINGLE PLAYLIST DETAILS (BUT NOT ITEMS)
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function update($playlistId)
	{
		// SAVE EDITS TO SINGLE PLAYLIST
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function destroy($playlistId)
	{
		// DELETE SINGLE PLAYLIST
	}

}
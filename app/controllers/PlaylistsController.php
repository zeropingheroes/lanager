<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\Playlists\Items\Item;
use View, Response, Input, Redirect, Request;

class PlaylistsController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('permission', array('only' => array('create', 'store', 'show', 'edit', 'update', 'destroy'))  );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$playlists = Playlist::all();
		
		if ( Request::ajax() ) return Response::json($playlists);

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
		$playlist = new Playlist;

		$playlist->name = Input::get('name');
		$playlist->playback_state = Input::get('playback_state');

		return $this->process( $playlist );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function show($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);
		
		if ( Request::ajax() ) return Response::json($playlist);

		return View::make('playlists.show')
					->with('title', $playlist->name . ' Playlist Screen')
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function update($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);

		if( Input::has('name') ) $playlist->name = Input::get('name');
		if( Input::has('playback_state') ) $playlist->playback_state = Input::get('playback_state');
		
		return $this->process( $playlist );

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function destroy($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);

		return $this->process( $playlist );
	}

}
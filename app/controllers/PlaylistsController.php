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
		
		return View::make('playlists.index')
					->with('title', 'Playlists')
					->with('playlists', $playlists);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$playlist = new Playlist;
		return View::make('playlists.create')
					->with('title','Create Playlist')
					->with('playlist',$playlist);
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

		if( !empty(Input::get('description')) )			$playlist->description = 			Input::get('description');
		if( !empty(Input::get('max_item_duration')) )	$playlist->max_item_duration = 		Input::get('max_item_duration');
		if( !empty(Input::get('max_item_duplicates')) ) $playlist->max_item_duplicates =	Input::get('max_item_duplicates');
		if( !empty(Input::get('user_skip_threshold')) ) $playlist->user_skip_threshold =	Input::get('user_skip_threshold');

		return $this->process( $playlist, 'playlists.index' );

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
		$playlist = Playlist::findOrFail($playlistId);

		return View::make('playlists.edit')
					->with('title','Edit Playlist')
					->with('playlist',$playlist);
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

		if( !empty(Input::get('description')) )			$playlist->description = 			Input::get('description');
		if( !empty(Input::get('max_item_duration')) )	$playlist->max_item_duration = 		Input::get('max_item_duration');
		if( !empty(Input::get('max_item_duplicates')) ) $playlist->max_item_duplicates =	Input::get('max_item_duplicates');
		if( !empty(Input::get('user_skip_threshold')) ) $playlist->user_skip_threshold =	Input::get('user_skip_threshold');

		return $this->process( $playlist, 'playlists.index' );

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
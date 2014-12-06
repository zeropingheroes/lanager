<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\Playlists\Items\Item,
	Zeropingheroes\Lanager\Playlists\PlaylistValidator;
use View, Input, Redirect, Notification;

class PlaylistsController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['create', 'store', 'show', 'edit', 'update', 'destroy'] ] );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$playlists = Playlist::all();
		
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
		$playlist->fill( Input::get() );

		$playlistValidator = PlaylistValidator::make( $playlist->toArray() )->scope('store');

		if ( $playlistValidator->fails() )
		{
			Notification::danger( $playlistValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$playlist->save();
		Notification::success('Playlist successfully stored');
		return Redirect::route('playlists.items.index', $playlist->id);
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
		$playlist->fill( Input::get() );

		$playlistValidator = PlaylistValidator::make( $playlist->toArray() )->scope('update');

		if ( $playlistValidator->fails() )
		{
			Notification::danger( $playlistValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$playlist->save();
		Notification::success('Playlist successfully updated');
		return Redirect::route('playlists.items.index', $playlist->id);

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

		$playlist->delete();
		Notification::success('Playlist successfully destroyed');
		return Redirect::back();
	}

}
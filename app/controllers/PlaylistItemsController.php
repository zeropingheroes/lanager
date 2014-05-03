<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\PlaylistItem;
use View, Response, Auth, Input, Redirect, Request;

class PlaylistItemsController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter( 'checkResourcePermission', array('only' => array('store', 'update', 'destroy')) );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($playlistId)
	{
		$playlist = Playlist::findOrFail($playlistId);
		$playlistItems = $playlist->playlistItems();

		if ( Request::ajax() )
		{
			$playlistItems = $playlist->playlistItems();

			// Filter based on query string
			if ( Input::has('playback_state') ) $playlistItems->where('playback_state', Input::get('playback_state') );
			if ( Input::has('order_by') ) $playlistItems->orderBy( Input::get('order_by'), Input::get('sort', 'asc') );
			if ( Input::has('skip') ) $playlistItems->skip( Input::get('skip') );
			if ( Input::has('take') ) $playlistItems->take( Input::get('take') );
			
			// Allow related models to be returned
			if ( Input::has('with') ) $playlistItems->with( explode(',', Input::get('with')) );

			return Response::json( $playlistItems->get() );
		}

		if( Input::get('history') )
		{
			$playlistItems = $playlistItems
				->where('playback_state', '!=', 0)
				->orderBy('created_at', 'desc');

			$title = 'Playlist History';
			$history = true;
		}
		else
		{
			$playlistItems = $playlistItems
				->where('playback_state', 0)
				->orderBy('created_at', 'asc');			
			
			$title = 'Playlist';
			$history = false;
		}
		$playlistDuration = $playlistItems->sum('duration');
		$playlistItems = $playlistItems->paginate(10);

		return View::make('playlists.playlistitems.index')
					->with('title', $title)
					->with('playlist', $playlist)
					->with('playlistDuration', $playlistDuration)
					->with('playlistItems', $playlistItems)
					->with('history', $history);
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
		$playlistItem->playlist_id = $playlistId;
		$playlistItem->user_id = Auth::user()->id;
		$playlistItem->url = Input::get('url');

		if( ! $playlistItem->save() )
		{
			if ( Request::ajax() ) return Response::json($playlistItem->errors(), 400);
			return Redirect::route('playlists.playlistitems.index', array('playlist' => $playlistId))->withErrors($playlistItem->validationErrors);
		}

		if ( Request::ajax() ) return Response::json($playlistItem, 201);
		return Redirect::route('playlists.playlistitems.index', array('playlist' => $playlistId));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @param  int  $playlistItemId
	 * @return Response
	 */
	public function show($playlistId, $playlistItemId)
	{
		$playlistItem = Playlist::findOrFail($playlistId)->playlistitems()->findOrFail($playlistItemId);
		
		if ( Request::ajax() ) return Response::json($playlistItem);
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
			$playlistItem = Playlist::findOrFail($playlistId)->playlistitems()->findOrFail($playlistItemId);
				
			if( Input::has('playback_state') ) $playlistItem->playback_state = Input::get('playback_state');
			if( Input::has('skip_reason') && $playlistItem->playback_state == 2 ) $playlistItem->skip_reason = Input::get('skip_reason');

			if ( ! $playlistItem->save() )
			{
				return Response::json($playlistItem->errors(), 400);
			}
			return Response::json($playlistItem);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistId, $playlistItemId)
	{
		$playlistItem = Playlist::findOrFail($playlistId)->playlistitems()->findOrFail($playlistItemId);

		if ( Request::ajax() ) return Response::json( $playlistItem->destroy($playlistItemId), 204);
	}

}
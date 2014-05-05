<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseController;
use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\Playlist\Item;
use View, Response, Auth, Input, Redirect, Request;

class ItemsController extends BaseController {

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
		$items = $playlist->items();

		if ( Request::ajax() )
		{
			$items = $playlist->items();

			// Filter based on query string
			if ( Input::has('playback_state') ) $items->where('playback_state', Input::get('playback_state') );
			if ( Input::has('order_by') ) $items->orderBy( Input::get('order_by'), Input::get('sort', 'asc') );
			if ( Input::has('skip') ) $items->skip( Input::get('skip') );
			if ( Input::has('take') ) $items->take( Input::get('take') );
			
			// Allow related models to be returned
			if ( Input::has('with') ) $items->with( explode(',', Input::get('with')) );

			return Response::json( $items->get() );
		}

		if( Input::get('history') )
		{
			$items = $items
				->where('playback_state', '!=', 0)
				->orderBy('created_at', 'desc');

			$title = 'Playlist History';
			$history = true;
		}
		else
		{
			$items = $items
				->where('playback_state', 0)
				->orderBy('created_at', 'asc');			
			
			$title = 'Playlist';
			$history = false;
		}
		$duration = $items->sum('duration');
		$items = $items->paginate(10);

		return View::make('playlists.items.index')
					->with('title', $title)
					->with('playlist', $playlist)
					->with('duration', $duration)
					->with('items', $items)
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

		$item = new Item;
		$item->playlist_id = $playlistId;
		$item->user_id = Auth::user()->id;
		$item->url = Input::get('url');

		if( ! $item->save() )
		{
			if ( Request::ajax() ) return Response::json($item->errors(), 400);
			return Redirect::route('playlists.items.index', array('playlist' => $playlistId))->withErrors($item->validationErrors);
		}

		if ( Request::ajax() ) return Response::json($item, 201);
		return Redirect::route('playlists.items.index', array('playlist' => $playlistId));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @param  int  $itemId
	 * @return Response
	 */
	public function show($playlistId, $itemId)
	{
		$item = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId);
		
		if ( Request::ajax() ) return Response::json($item);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($playlistId, $itemId)
	{
		if ( Request::ajax() )
		{
			$item = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId);
				
			if( Input::has('playback_state') ) $item->playback_state = Input::get('playback_state');
			if( Input::has('skip_reason') && $item->playback_state == 2 ) $item->skip_reason = Input::get('skip_reason');

			if ( ! $item->save() )
			{
				return Response::json($item->errors(), 400);
			}
			return Response::json($item);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistId, $itemId)
	{
		$item = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId);

		if ( Request::ajax() ) return Response::json( $item->destroy($itemId), 204);
	}

}
<?php namespace Zeropingheroes\Lanager\Playlists\Items;

use Zeropingheroes\Lanager\BaseController;
use Zeropingheroes\Lanager\Models\Playlist,
	Zeropingheroes\Lanager\Models\Playlist\Item,
	Zeropingheroes\Lanager\Models\Playlist\Item\Vote;
use Response, Auth, Request;

class VotesController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter( 'checkResourcePermission', array('only' => array('store', 'destroy')) );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($playlistId, $itemId)
	{
		$item = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId);

		$vote = new Vote;
		$vote->playlist_item_id = $itemId;
		$vote->user_id = Auth::user()->id;
		$vote->vote = -1; // down vote

		if( ! $vote->save() )
		{
			if ( Request::ajax() ) return Response::json($vote->errors(), 400);
		}

		if ( Request::ajax() ) return Response::json($vote, 201);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($playlistId, $itemId, $voteId)
	{
		$vote = Playlist::findOrFail($playlistId)->items()->findOrFail($itemId)->votes()->findOrFail($voteId);

		if ( Request::ajax() ) return Response::json( $vote->destroy($voteId), 204);
	}

}
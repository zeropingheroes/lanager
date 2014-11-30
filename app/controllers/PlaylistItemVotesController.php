<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\BaseController;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\Playlists\Items\Votes\Vote,
	Zeropingheroes\Lanager\Playlists\Items\Votes\VoteValidator;
use Auth, Redirect, Notification;

class PlaylistItemVotesController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['store', 'destroy']] );
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
		$vote->playlist_item_id = $item->id;
		$vote->user_id = Auth::user()->id;
		$vote->vote = -1; // down vote

		$voteValidator = VoteValidator::make( $vote->toArray() )->scope('store');

		if ( $voteValidator->fails() )
		{
			Notification::danger( $voteValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$vote->save();
		Notification::success('Vote successfully stored');
		return Redirect::route('playlists.items.index', $item->playlist_id);
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

		$vote->delete();
		Notification::success('Vote successfully destroyed');
		return Redirect::back();
	}

}
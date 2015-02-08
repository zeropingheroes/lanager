<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use League\Fractal;

class PlaylistItemVoteTransformer extends Fractal\TransformerAbstract {
	
	public function transform(PlaylistItemVote $playlistItemVote)
	{
		return [
			'id'				=> (int) $playlistItemVote->id,
			'playlist_item_id'	=> (int) $playlistItemVote->playlist_item_id,
			'user'				=> [
				'id'				=> $playlistItemVote->user->id,
				'username'			=> $playlistItemVote->user->username,
				'avatar'			=> $playlistItemVote->user->avatar,
			],
		];
	}
}
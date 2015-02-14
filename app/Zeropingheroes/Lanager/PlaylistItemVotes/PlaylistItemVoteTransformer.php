<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use League\Fractal;

use Zeropingheroes\Lanager\Users\UserTransformer;


class PlaylistItemVoteTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = [
		'user',
	];

	public function transform(PlaylistItemVote $playlistItemVote)
	{
		return [
			'id'				=> (int) $playlistItemVote->id,
			'playlist_item_id'	=> (int) $playlistItemVote->playlist_item_id,
			'user_id'			=> (int) $playlistItemVote->user_id,
		];
	}

	public function includeUser(PlaylistItemVote $playlistItemVote)
	{
		return $this->collection($playlistItemVote->user()->get(), new UserTransformer);
	}
}
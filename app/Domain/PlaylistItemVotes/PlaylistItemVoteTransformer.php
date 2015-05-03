<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItemVotes;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;

class PlaylistItemVoteTransformer extends TransformerAbstract {

	/**
	 * Default related resources to include in transformed output
	 * @var array
	 */
	protected $defaultIncludes = [
		'user',
	];

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform( PlaylistItemVote $playlistItemVote )
	{
		return [
			'id'				=> (int) $playlistItemVote->id,
			'playlist_item_id'	=> (int) $playlistItemVote->playlist_item_id,
			'user_id'			=> (int) $playlistItemVote->user_id,
		];
	}

	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includeUser( PlaylistItemVote $playlistItemVote )
	{
		return $this->item( $playlistItemVote->user()->first(), new UserTransformer);
	}
}
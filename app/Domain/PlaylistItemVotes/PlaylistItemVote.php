<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItemVotes;

use Zeropingheroes\Lanager\Domain\BaseModel;

class PlaylistItemVote extends BaseModel {

	protected $fillable = [ 'playlist_item_id', 'user_id' ];

	/**
	 * A single playlist item vote belongs to a single playlist item
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItem()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItem');
	}

	/**
	 * A single playlist item vote belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

}
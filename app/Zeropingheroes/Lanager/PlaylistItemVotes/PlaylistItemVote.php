<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Zeropingheroes\Lanager\BaseModel;

class PlaylistItemVote extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['playlist_item_id', 'user_id'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVoteValidator';

	/**
	 * A single playlist item vote belongs to a single playlist item
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItem()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

	/**
	 * A single playlist item vote belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
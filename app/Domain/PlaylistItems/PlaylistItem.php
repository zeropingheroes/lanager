<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use Zeropingheroes\Lanager\Domain\BaseModel;

class PlaylistItem extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['playlist_id', 'user_id', 'url', 'title', 'playback_state', 'duration', 'skip_reason'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['skip_reason'];

	/**
	 * Fields that have a useful default set in the database
	 * If any of these fields are empty when creating or updating the model should be set to this default
	 * @var array
	 */
	protected $optional = ['playback_state'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemValidator';

	/**
	 * A single playlist item belongs to a single playlist
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlist()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Playlists\Playlist');
	}

	/**
	 * A single playlist item belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

	/**
	 * A single playlist item has many playlist item (skip) votes
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItemVotes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVote', 'playlist_item_id');
	}

}
<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use Zeropingheroes\Lanager\Domain\BaseModel;

class PlaylistItem extends BaseModel {

	protected $fillable = [ 'playlist_id', 'user_id', 'url', 'title', 'playback_state', 'duration', 'skip_reason' ];

	protected $nullable = [ 'skip_reason' ];

	protected $optional = [ 'playback_state' ];

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
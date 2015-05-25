<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Playlist extends BaseModel {

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Domain\Playlists\PlaylistPresenter';

	protected $fillable = [ 'name', 'description', 'playback_state', 'max_item_duration', 'user_skip_threshold' ];

	protected $nullable = [ 'description' ];

	protected $optional = [ 'playback_state', 'max_item_duration', 'user_skip_threshold' ];

	/**
	 * A single playlist has many playlist items
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItems()
	{
		return $this->hasMany( 'Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItem' );
	}

	/**
	 * Pseudo-relation: A playlist's currently unplayed item
	 * @return object
	 */
	public function currentItem()
	{
		return $this->playlistItems()->where('playback_state', 0)->orderBy('created_at')->first();
	}

}
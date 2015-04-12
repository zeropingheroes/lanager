<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;


class Playlist extends BaseModel {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name', 'description', 'playback_state', 'max_item_duration', 'user_skip_threshold'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['description'];

	/**
	 * Fields that have a useful default set in the database
	 * If any of these fields are empty when creating or updating the model should be set to this default
	 * @var array
	 */
	protected $optional = ['playback_state', 'max_item_duration', 'user_skip_threshold'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Playlists\PlaylistValidator';

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Playlists\PlaylistPresenter';

	/**
	 * A single playlist has many playlist items
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

}
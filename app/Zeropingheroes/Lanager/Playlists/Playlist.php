<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;


class Playlist extends BaseModel {

	protected $fillable = ['name', 'description', 'playback_state', 'max_item_duration', 'user_skip_threshold'];
	protected $nullable = ['description'];
	protected $optional = ['playback_state', 'max_item_duration', 'user_skip_threshold'];

	public $validator = 'Zeropingheroes\Lanager\Playlists\PlaylistValidator';

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Playlists\PlaylistPresenter';

	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

}
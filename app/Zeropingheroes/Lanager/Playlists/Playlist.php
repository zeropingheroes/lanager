<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseModel;

class Playlist extends BaseModel {

	protected $fillable = ['name', 'description', 'playback_state', 'max_item_duration', 'user_skip_threshold'];

	protected $nullable = ['description', 'max_item_duration', 'user_skip_threshold'];

	public $validator = 'Zeropingheroes\Lanager\Playlists\PlaylistValidator';

	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

}
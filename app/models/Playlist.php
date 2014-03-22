<?php namespace Zeropingheroes\Lanager\Models;

class Playlist extends BaseModel {
	
	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\PlaylistItem');
	}

}
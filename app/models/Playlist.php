<?php namespace Zeropingheroes\Lanager\Models;

class Playlist extends BaseModel {

	public static $rules = array(
		'name'				=> 'required|max:255',
		'playback_state'	=> 'required'
	);
	
	public function playlistItems()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\PlaylistItem');
	}

}
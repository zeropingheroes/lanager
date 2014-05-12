<?php namespace Zeropingheroes\Lanager\Models;

class Playlist extends BaseModel {

	public static $rules = array(
		'name'				=> 'max:255',
		'playback_state'	=> 'required'
	);
	
	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Item');
	}

}
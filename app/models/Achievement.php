<?php namespace Zeropingheroes\Lanager\Models;

class Achievement extends BaseModel {
	
	public static $rules = array(
		'name'			=> 'required|max:255',
	);

	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Award');
	}

	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Models\User');
	}

}
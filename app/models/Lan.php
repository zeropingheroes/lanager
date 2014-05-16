<?php namespace Zeropingheroes\Lanager\Models;

class Lan extends BaseModel {

	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Playlist\Award');
	}

}
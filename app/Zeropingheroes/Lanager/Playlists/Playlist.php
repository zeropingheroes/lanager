<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseModel;

class Playlist extends BaseModel {

	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

}
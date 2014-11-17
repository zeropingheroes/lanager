<?php namespace Zeropingheroes\Lanager\Playlists;

use Zeropingheroes\Lanager\BaseModel;

class Playlist extends BaseModel {

	public static $rules = array(
		'name'					=> 'required|max:255|unique:playlists',
		'description'			=> 'max:255',
		'max_item_duration'		=> 'numeric|min:1',
		'max_item_duplicates'	=> 'numeric|min:0',
		'user_skip_threshold'	=> 'numeric|min:1|max:100',
	);
	
	public function items()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

}
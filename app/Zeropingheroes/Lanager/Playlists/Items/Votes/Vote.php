<?php namespace Zeropingheroes\Lanager\Playlists\Items\Votes;

use Zeropingheroes\Lanager\BaseModel;

class Vote extends BaseModel {

	protected $table = 'playlist_item_votes';
	
	public function item()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
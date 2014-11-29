<?php namespace Zeropingheroes\Lanager\Playlists\Items;

use Zeropingheroes\Lanager\BaseModel;

class Item extends BaseModel {

	protected $table = 'playlist_items';

	public function getDates()
	{
		return array('created_at', 'updated_at', 'played_at');
	}

	public function playlist()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Playlists\Playlist');
	}

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function votes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Playlists\Items\Votes\Vote', 'playlist_item_id');
	}

	public function scopeUnplayed($query)
	{
		return $query->where('playback_state', 0)->orderBy('created_at');
	}

}
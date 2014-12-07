<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Zeropingheroes\Lanager\BaseModel;

class PlaylistItem extends BaseModel {

	protected $fillable = ['playlist_id', 'user_id', 'url', 'title', 'playback_state', 'duration', 'skip_reason', 'played_at'];

	public $validator = 'Zeropingheroes\Lanager\PlaylistItems\PlaylistItemValidator';

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

	public function playlistItemVotes()
	{
		return $this->hasMany('Zeropingheroes\Lanager\PlaylistItemVotes\Vote', 'playlist_item_id');
	}

	public function scopeUnplayed($query)
	{
		return $query->where('playback_state', 0)->orderBy('created_at');
	}
}
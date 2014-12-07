<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Zeropingheroes\Lanager\BaseModel;

class PlaylistItemVote extends BaseModel {

	protected $table = 'playlist_item_votes';

	public $validator = 'Zeropingheroes\Lanager\PlaylistItemVotes\PlaylistItemVoteValidator';

	public function playlistItem()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\PlaylistItems\PlaylistItem');
	}

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
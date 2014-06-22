<?php namespace Zeropingheroes\Lanager\Playlists\Items\Votes;

use Zeropingheroes\Lanager\BaseModel;

use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\Playlists\Items\Item;
use Illuminate\Support\MessageBag;
use Auth;

class Vote extends BaseModel {

	protected $table = 'playlist_item_votes';

	public static $rules = array(
		'vote'	=> 'required|in:-1,1',
	);
	
	public function item()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Playlists\Items\Item');
	}

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function beforeSave()
	{
		// Perform validation
		$errors = new MessageBag;
		
		$alreadyVoted = Vote::where('user_id', Auth::user()->id)
			->where('playlist_item_id', $this->playlist_item_id)
			->count();

		if( $alreadyVoted )
		{
			$this->validationErrors = $errors->add('error', 'You have already cast a vote on this item.' );
			return false;
		}

		if( $this->playlist_item_id != Item::unplayed()->first()->id )
		{
			$this->validationErrors = $errors->add('error', 'This item is not currently playing.' );
			return false;
		}

	}
}
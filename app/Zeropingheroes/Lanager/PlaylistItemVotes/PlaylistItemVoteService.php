<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\PlaylistItems\PlaylistItem;

use Auth;

class PlaylistItemVoteService extends NestedResourceService {

	public $resource = 'playlists.items.votes';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
			new PlaylistItemVote,
		];
		parent::__construct($listener, $models);
	}

	public function store( array $ids, $input)
	{
		unset($input); // no input needed
		$input['user_id'] = Auth::user()->id; // set user id to current user
		
		return parent::store($ids, $input);
	}

	public function update( array $ids, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}

}
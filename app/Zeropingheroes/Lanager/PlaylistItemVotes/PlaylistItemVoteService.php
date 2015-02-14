<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\PlaylistItems\PlaylistItem;

class PlaylistItemVoteService extends NestedResourceService {

	public $resource = 'playlist item vote';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
			new PlaylistItemVote,
		];
		parent::__construct($listener, $models);
	}

	public function update( array $ids, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}

}
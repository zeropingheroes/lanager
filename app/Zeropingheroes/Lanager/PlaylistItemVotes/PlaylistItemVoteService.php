<?php namespace Zeropingheroes\Lanager\PlaylistItemVotes;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist,
	Zeropingheroes\Lanager\PlaylistItems\PlaylistItem;

use Auth;

class PlaylistItemVoteService extends NestedResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	public $resource = 'playlists.items.votes';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
			new PlaylistItemVote,
		];
		parent::__construct($listener, $models);
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function store( array $ids, $input)
	{
		unset($input); // no input needed
		$input['user_id'] = Auth::user()->id; // set user id to current user
		
		return parent::store($ids, $input);
	}

	/**
	 * Block attempts to update the resource
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update( array $ids, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}

}
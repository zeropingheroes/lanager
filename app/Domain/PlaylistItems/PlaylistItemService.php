<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use Zeropingheroes\Lanager\Domain\NestedResourceService;
use Zeropingheroes\Lanager\Domain\Playlists\Playlist;
use Auth;
use Authority;
use Config;

class PlaylistItemService extends NestedResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	public $resource = 'playlists.items';

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
		];
		parent::__construct($listener, $models);
	}

	/**
	 * Fetch the given item's metadata
	 * @param  string $url URL of the item
	 * @return object PlayableItem      Playable item
	 */
	private function fetchItem( $url )
	{
		$providers = Config::get('lanager/playlist.providers');
		return (new PlayableItemFactory)->create( $url, $providers);
	}

	/**
	 * Filter user input for data integrity and security
	 * @param  array $input raw input from user
	 * @return array $input input, filtered
	 */
	private function filterInput( $input, $scope )
	{
		if( Authority::can('manage', 'playlists') )
		{
			$input = array_only($input, ['url', 'playback_state', 'played_at', 'skip_reason']);
			if( $scope != 'update') $input['user_id'] = Auth::user()->id; // if playlist manager is posting new video default to their user id
		}
		else // only accept URL from standard users
		{
			$input = array_only($input, ['url']);
			$input['user_id'] = Auth::user()->id;
		}
		return $input;
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function store( array $ids, $input)
	{
		$input = $this->filterInput($input, 'store');

		try
		{
			$input = array_merge( $input, $this->fetchItem( $input['url'] )->toArray() );
		}
		catch( UnplayableItemException $e)
		{
			$this->errors = $e->getMessage();
			return $this->listener->storeFailed( $this );
		}

		// pass new input containing metadata to base service provider for validation and storage
		return parent::store($ids, $input);
	}

	/**
	 * Update the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update( array $ids, $input)
	{
		$input = $this->filterInput($input, 'update');

		if( array_key_exists('url', $input) ) // if the URL has been changed, fetch the new item
		{
			try
			{
				$input = array_merge( $input, $this->fetchItem( $input['url'] )->toArray() );
			}
			catch( UnplayableItemException $e)
			{
				$this->errors = $e->getMessage();
				return $this->listener->updateFailed( $this );
			}
		}

		// pass new input containing metadata to base service provider for validation and storage
		return parent::update($ids, $input);
	}
}
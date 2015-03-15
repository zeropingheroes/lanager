<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist;

use Auth, Authority, Config;

class PlaylistItemService extends NestedResourceService {

	public $resource = 'playlists.items';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
		];
		parent::__construct($listener, $models);
	}

	private function fetchItem( $url )
	{
		$providers = Config::get('lanager/playlist.providers');
		return (new PlayableItemFactory)->create( $url, $providers);
	}

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
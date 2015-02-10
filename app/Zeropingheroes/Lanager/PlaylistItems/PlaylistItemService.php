<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Playlists\Playlist;

use Auth, Config;

class PlaylistItemService extends NestedResourceService {

	public $resource = 'playlist item';

	public function __construct( $listener )
	{
		$models = [
			new Playlist,
			new PlaylistItem,
		];
		parent::__construct($listener, $models);
	}

	public function store( array $ids, $input)
	{
		$input = array_only($input, ['url']); // filter out everything from the input but the URL
		$input['user_id'] = Auth::user()->id;

		$providers = Config::get('lanager/playlist.providers');

		try // ...to pull in the item (by URL) from a provider
		{
			$playableItem = (new PlayableItemFactory)->create( $input['url'], $providers);
			$input = array_merge($input, $playableItem->toArray());
		}
		catch(UnplayableItemException $e)
		{
			$this->errors = $e->getMessage();
			return $this->listener->storeFailed( $this );
		}

		// pass input to default service provider
		parent::store($ids, $input);
	}
}
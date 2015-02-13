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

	public function fetchItem( $url )
	{
		$providers = Config::get('lanager/playlist.providers');
		return (new PlayableItemFactory)->create( $url, $providers);
	}

	public function store( array $ids, $input)
	{
		$input = array_only($input, ['url']);
		$input['user_id'] = Auth::user()->id;

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
		$input = array_only($input, ['url']);
		$input['user_id'] = Auth::user()->id;

		try
		{
			$input = array_merge( $input, $this->fetchItem( $input['url'] )->toArray() );
		}
		catch( UnplayableItemException $e)
		{
			$this->errors = $e->getMessage();
			return $this->listener->updateFailed( $this );
		}

		// pass new input containing metadata to base service provider for validation and storage
		return parent::update($ids, $input);
	}
}
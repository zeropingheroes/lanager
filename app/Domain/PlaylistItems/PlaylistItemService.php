<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByCreatedAt;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByUser;
use Zeropingheroes\Lanager\Domain\AuthorisationException;
use DomainException;
use Config;
use Duration;
use Carbon\Carbon;

class PlaylistItemService extends ResourceService {

	protected $eagerLoad = [ 'playlistItemVotes', 'user.state.application' ];

	use FilterableByCreatedAt;

	use FilterableByUser;

	public function __construct()
	{
		parent::__construct(
			new PlaylistItem,
			new PlaylistItemValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole( 'Playlists Admin' );
	}

	protected function destroyAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	public function store( $input )
	{
		$providers = Config::get('lanager/playlist.providers');
		$item = (new PlayableItemFactory)->create( $input['url'], $providers);

		$input = array_merge( $input, $item->toArray() );

		$input['user_id'] = $this->user->id();
		$input['playback_state'] = 0;
		$input['skip_reason'] = null;

		return parent::store( $input );
	}

	public function update( $id, $input)
	{
		if ( isset( $input['url'] ) OR isset( $input['title'] ) OR isset( $input['duration'] ) OR isset( $input['user_id'] ) )
			throw new DomainException( 'The URL, title, duration and user ID fields are not updateable' );

		return parent::update( $id, $input );
	}

	protected function rulesOnStore( $input )
	{
		$past = (new Carbon)->subMinutes(15);

		$recentPlaylistItems = ( new self )->filterCreatedAfter( $past )->filterByUser( $input['user_id'] )->get();

		if ( $recentPlaylistItems->count() != 0 )
			throw new DomainException( 'You have posted too recently - please wait a while and try again' );

		$playlist = (new PlaylistService)->single( $input['playlist_id'] );
		
		if ( $playlist->max_item_duration < $input['duration'] )
			throw new DomainException( 'The item\'s duration must not exceed ' . Duration::longFormat($playlist->max_item_duration) );

		$duplicates = ( new self )->filterByUrl( $input['url'] )->filterByPlaylist( $input['playlist_id'] )->get();

		if ( $duplicates->count() != 0 )
			throw new DomainException( 'The item has already been added to this playlist' );

	}

	protected function rulesOnUpdate( $input, $original )
	{
		if ( $input['playback_state'] == 2 AND empty( $input['skip_reason'] ) )
			throw new DomainException( 'You must specify a reason when skipping an item' );
	}

	protected function rulesOnDestroy( $input )
	{
		if ( ! $this->user->hasRole( 'Playlists Admin' ) )
		{
			if ( $input['user_id'] != $this->user->id() )
				throw new AuthorisationException( 'You may only delete your own playlist items' );
		}
	}

	/**
	 * Filter by a given playlist ID
	 * @param  integer     $playlistId
	 * @return self
	 */
	public function filterByPlaylist( $playlistId )
	{
		$this->model = $this->model->where( 'playlist_id', $playlistId );

		return $this;
	}

	/**
	 * Filter by a given URL
	 * @param  string       $url
	 * @return self
	 */
	public function filterByUrl( $url )
	{
		$this->model = $this->model->where( 'url', $url );

		return $this;
	}

	/**
	 * Get all unplayed items
	 * @return Collection       Collection of items
	 */
	public function unplayed()
	{
		$this->runChecks( 'read' );

		$this->model = $this->model->where( 'playback_state', 0 )->orderBy('created_at');

		return $this->get();
	}

	/**
	 * Get all played items
	 * @return Collection       Collection of items
	 */
	public function played()
	{
		$this->runChecks( 'read' );

		$this->model = $this->model->where( 'playback_state', 1 )->orderBy('updated_at', 'desc');

		return $this->get();
	}

	/**
	 * Get all skipped items
	 * @return Collection       Collection of items
	 */
	public function skipped()
	{
		$this->runChecks( 'read' );

		$this->model = $this->model->where( 'playback_state', 2 )->orderBy('updated_at', 'desc');

		return $this->get();
	}

}
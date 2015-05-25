<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItemVotes;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemService;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByUser;
use Zeropingheroes\Lanager\Domain\AuthorisationException;
use DomainException;

class PlaylistItemVoteService extends ResourceService {
	
	protected $eagerLoad = [ 'user.state.application' ];

	use FilterableByUser;

	public function __construct()
	{
		parent::__construct(
			new PlaylistItemVote,
			new PlaylistItemVoteValidator
		);
	}

	protected function readAuthorised()
	{
		return $this->user->hasRole('Playlists Admin');
	}

	protected function storeAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	protected function destroyAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	public function store( $input )
	{
		$input['user_id'] = $this->user->id();

		parent::store( $input );
	}

	protected function rulesOnStore( $input )
	{
		$votes = (new self)->filterByUser( $input['user_id'] )->filterByPlaylistItem( $input['playlist_item_id'] )->all();
		
		if ( $votes->count() != 0 )
			throw new DomainException( 'You have already voted to skip this item' );
	}

	/**
	 * Filter by a given playlist item ID
	 * @param  integer     $playlistItemId
	 * @return self
	 */
	public function filterByPlaylistItem( $playlistItemId )
	{
		$this->model = $this->model->where( 'playlist_item_id', $playlistItemId );

		return $this;
	}

}
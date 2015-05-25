<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use Zeropingheroes\Lanager\Domain\ResourceService;

class PlaylistService extends ResourceService {

	protected $orderBy = [ 'name' ];

	public function __construct()
	{
		parent::__construct(
			new Playlist,
			new PlaylistValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('Playlists Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('Playlists Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Playlists Admin');
	}

}
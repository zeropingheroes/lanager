<?php namespace Zeropingheroes\Lanager\Domain\Lans;

use Zeropingheroes\Lanager\Domain\ResourceService;

class LanService extends ResourceService  {

	protected $orderBy = [ 'start' ];

	protected $eagerLoad = [ 'userAchievements.achievement', 'userAchievements.user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new Lan,
			new LanValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

}
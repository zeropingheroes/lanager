<?php namespace Zeropingheroes\Lanager\Domain\UserAchievements;

use Zeropingheroes\Lanager\Domain\ResourceService;

class UserAchievementService extends ResourceService {

	protected $orderBy = [ [ 'lan_id', 'desc' ], [ 'user_id', 'desc' ] ];

	protected $eagerLoad = [ 'achievement', 'lan', 'user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new UserAchievement,
			new UserAchievementValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole( 'Achievements Admin' );
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole( 'Achievements Admin' );
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole( 'Achievements Admin' );
	}

}
<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use Zeropingheroes\Lanager\Domain\ResourceService;

class AchievementService extends ResourceService {

	protected $orderBy = [ 'name' ];

	protected $eagerLoad = [ 'userAchievements', 'userAchievements.lan', 'userAchievements.user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new Achievement,
			new AchievementValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function filter()
	{
		// Todo: re-implement hidden achievements
		// if ( ! $this->user->hasRole( 'Achievements Admin' ) )
		// 	$this->model = $this->model->where( 'visible', true );
	}

}
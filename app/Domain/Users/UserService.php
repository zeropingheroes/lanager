<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Zeropingheroes\Lanager\Domain\ResourceService;

class UserService extends ResourceService {

	protected $orderBy = [ 'username' ];

	protected $eagerLoad = [ 'state.application', 'userAchievements', 'roles' ];

	public function __construct()
	{
		parent::__construct(
			new User,
			new UserValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Super Admin');
	}

	protected function filter()
	{
		if ( ! $this->user->hasRole( 'Super Admin' ) )
			$this->model = $this->model->where( 'visible', true );
	}
}
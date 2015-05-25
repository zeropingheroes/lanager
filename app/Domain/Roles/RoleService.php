<?php namespace Zeropingheroes\Lanager\Domain\Roles;

use Zeropingheroes\Lanager\Domain\ResourceService;

class RoleService extends ResourceService {

	protected $orderBy = [ 'name' ];

	protected $eagerLoad = [ 'userRoles.role', 'userRoles.user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new Role,
			new RoleValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole( 'Super Admin' );
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole( 'Super Admin' );
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole( 'Super Admin' );
	}

}
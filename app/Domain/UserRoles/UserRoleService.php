<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Users\UserService;
use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByTimestamps;
use DomainException;

class UserRoleService extends ResourceService {

	use FilterableByTimestamps;

	protected $model = 'Zeropingheroes\Lanager\Domain\UserRoles\UserRole';

	protected $eagerLoad = [ 'role', 'user.state.application' ];

	public function store( $input )
	{
		$input['assigned_by'] = $this->user->id();

		parent::store( $input );
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole( 'Super Admin' );
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole( 'Super Admin' );
	}

	protected function validationRulesOnStore( $input )
	{
		return [
			'user_id'			=> [ 'required', 'exists:users,id' ],
			'role_id'			=> [ 'required', 'exists:roles,id' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		return $this->validationRulesOnStore( $input );
	}

	protected function domainRulesOnStore( $input )
	{
		$user = ( new UserService )->single( $input['user_id'] );
		$role = ( new RoleService )->single( $input['role_id'] );

		if ( $user->hasRole( $role->name ) )
			throw new DomainException( 'User already has this role (or above) assigned to them' );
	}

}
<?php namespace Zeropingheroes\Lanager\Domain\Roles;

use Zeropingheroes\Lanager\Domain\ResourceService;

class RoleService extends ResourceService {

	protected $model = 'Zeropingheroes\Lanager\Domain\Roles\Role';

	protected $orderBy = [ 'name' ];

	protected $eagerLoad = [ 'userRoles.role', 'userRoles.user.state.application' ];

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

	protected function validationRulesOnStore( $input )
	{
		return [
			'name' => [ 'required', 'max:255', 'unique:roles,name' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		$rules = $this->validationRulesOnStore( $input );

		// Exclude current event type from uniqueness test
		$rules['name'] = [ 'required', 'max:255', 'unique:roles,name,' . $input['id'] ];

		return $rules;
	}

}
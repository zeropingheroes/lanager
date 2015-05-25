<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class UserRoleValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'user_id'		=> 'required|exists:users,id',
		'role_id'		=> 'required|exists:roles,id',
	];

}
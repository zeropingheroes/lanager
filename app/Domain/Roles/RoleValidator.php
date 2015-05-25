<?php namespace Zeropingheroes\Lanager\Domain\Roles;

use Fadion\ValidatorAssistant\ValidatorAssistant;
use Zeropingheroes\Lanager\Domain\InputValidatorContract;

class RoleValidator extends ValidatorAssistant implements InputValidatorContract {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'		=> 'required|max:255|unique:roles,name,{id}',
	];

}
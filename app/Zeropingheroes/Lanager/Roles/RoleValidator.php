<?php namespace Zeropingheroes\Lanager\Roles;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class RoleValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'name'		=> 'required|max:255',
	];

}
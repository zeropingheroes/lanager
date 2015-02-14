<?php namespace Zeropingheroes\Lanager\Roles;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class RoleValidator extends ValidatorAssistant {

	protected $rules = [
		'name'		=> 'required|max:255',
	];

}
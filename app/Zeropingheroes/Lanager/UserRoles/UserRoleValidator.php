<?php namespace Zeropingheroes\Lanager\UserRoles;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class UserRoleValidator extends ValidatorAssistant {

	/**
	 * Validation rules to enforce for each field
	 * @var array
	 */
	protected $rules = [
		'user_id'		=> 'required|exists:users,id|unique:user_roles,user_id,NULL,id,role_id,{role_id}',
		'role_id'		=> 'required|exists:roles,id',
	];

	/**
	 * Custom validation messages
	 * @var array
	 */
	protected $messages = [
		'user_id.unique' => 'The role has already been assigned to that user.',
	];

	/**
	 * Processing to carry out before running validation
	 */
	protected function before()
	{
		// Bind the ID so it can be used in the validation rules
		if( isset($this->inputs['role_id']) ) $this->bind('role_id', $this->inputs['role_id']);
	}

}
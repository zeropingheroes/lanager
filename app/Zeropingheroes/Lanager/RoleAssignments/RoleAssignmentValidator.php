<?php namespace Zeropingheroes\Lanager\RoleAssignments;

use Fadion\ValidatorAssistant\ValidatorAssistant;

class RoleAssignmentValidator extends ValidatorAssistant {

	protected $rules = [
		'user_id'		=> 'required|exists:users,id|unique:role_user,user_id,NULL,id,role_id,{role_id}',
		'role_id'		=> 'required|exists:roles,id',
	];

	protected $messages = [
		'user_id.unique' => 'The role has already been assigned to that user.',
	];

	protected function before()
	{
		$this->bind('role_id', $this->inputs['role_id']);
	}

}
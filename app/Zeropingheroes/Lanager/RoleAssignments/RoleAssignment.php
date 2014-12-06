<?php namespace Zeropingheroes\Lanager\RoleAssignments;

use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\Users\User;
use Illuminate\Support\MessageBag;

class RoleAssignment extends BaseModel {

	protected $table = 'role_user';

	protected $fillable = ['user_id', 'role_id'];

	public $validator = 'Zeropingheroes\Lanager\RoleAssignments\RoleAssignmentValidator';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function role()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Roles\Role');
	}

	public function beforeSave()
	{
		$errors = new MessageBag;

		$user = User::findOrFail($this->user_id);

		if( $user->hasRole($this->role->name) )
		{
			$this->validationErrors = $errors->add('error', 'The role has already been assigned to that user.' );
			return false;
		}
	}

}

<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Zeropingheroes\Lanager\Domain\Users\User;

class UserRole extends BaseModel {

	/**
	 * This model is stored in this unconventially named table
	 * @var string
	 */
	protected $table = 'user_roles';

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['user_id', 'role_id', 'assigned_by'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Domain\UserRoles\UserRoleValidator';

	/**
	 * A single user role (assignment) belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

	/**
	 * A single user role (assignment) belongs to a single role
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function role()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Roles\Role');
	}

	/**
	 * A single user role (assignment) belongs to a single user (who assigned the role)
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function assignedBy()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User', 'assigned_by');
	}

}

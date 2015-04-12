<?php namespace Zeropingheroes\Lanager\Roles;

use Zeropingheroes\Lanager\BaseModel;

class Role extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Roles\RoleValidator';

	/**
	 * A single role belongs to many users
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Users\User', 'user_roles');
	}

	/**
	 * A single role has many user roles (aka role assignments)
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function userRoles()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserRoles\UserRole');
	}

}
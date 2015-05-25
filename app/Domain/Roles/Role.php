<?php namespace Zeropingheroes\Lanager\Domain\Roles;

use Zeropingheroes\Lanager\Domain\BaseModel;

class Role extends BaseModel {

	protected $fillable = [ 'name' ];

	/**
	 * A single role belongs to many users
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function users()
	{
		return $this->belongsToMany( 'Zeropingheroes\Lanager\Domain\Users\User', 'user_roles' );
	}

	/**
	 * A single role has many user roles (aka role assignments)
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function userRoles()
	{
		return $this->hasMany( 'Zeropingheroes\Lanager\Domain\UserRoles\UserRole' );
	}

}
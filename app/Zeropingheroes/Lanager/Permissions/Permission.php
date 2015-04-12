<?php namespace Zeropingheroes\Lanager\Permissions;

use Zeropingheroes\Lanager\BaseModel;

class Permission extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['user_id', 'type', 'action', 'resource'];

}
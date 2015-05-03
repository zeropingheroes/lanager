<?php namespace Zeropingheroes\Lanager\Domain\Permissions;

use Zeropingheroes\Lanager\Domain\BaseModel;

class Permission extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['user_id', 'type', 'action', 'resource'];

}
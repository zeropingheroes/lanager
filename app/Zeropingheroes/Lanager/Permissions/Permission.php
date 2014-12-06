<?php namespace Zeropingheroes\Lanager\Permissions;

use Zeropingheroes\Lanager\BaseModel;

class Permission extends BaseModel {

	protected $fillable = ['user_id', 'type', 'action', 'resource'];

}
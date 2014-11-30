<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\BaseModel;

class Shout extends BaseModel {

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
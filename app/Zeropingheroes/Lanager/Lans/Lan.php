<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\BaseModel;

class Lan extends BaseModel {

	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Awards\Award');
	}

}
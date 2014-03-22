<?php namespace Zeropingheroes\Lanager\Models;

class EventType extends BaseModel {

	public function event()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Models\Event');
	}

}
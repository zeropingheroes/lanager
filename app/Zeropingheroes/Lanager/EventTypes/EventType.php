<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\BaseModel;

class EventType extends BaseModel {

	protected $fillable = ['name', 'colour'];

	protected $nullable = ['colour'];

	public $validator = 'Zeropingheroes\Lanager\EventTypes\EventTypeValidator';

	public function event()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Events\Event');
	}

}
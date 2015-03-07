<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class EventType extends BaseModel {

	protected $fillable = ['name', 'colour'];

	protected $nullable = ['colour'];

	public $validator = 'Zeropingheroes\Lanager\EventTypes\EventTypeValidator';

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\EventTypes\EventTypePresenter';

	public function events()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Events\Event');
	}

}
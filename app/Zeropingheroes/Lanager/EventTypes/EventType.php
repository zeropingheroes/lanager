<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class EventType extends BaseModel {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name', 'colour'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['colour'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\EventTypes\EventTypeValidator';

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\EventTypes\EventTypePresenter';

	/**
	 * A single event type has many events
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function events()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Events\Event');
	}

}
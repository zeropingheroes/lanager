<?php namespace Zeropingheroes\Lanager\Domain\Events;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Event extends BaseModel {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name', 'description', 'start', 'end', 'signup_opens', 'signup_closes', 'event_type_id', 'published'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['description', 'signup_opens', 'signup_closes'];

	/**
	 * Fields that have a useful default set in the database
	 * If any of these fields are empty when creating or updating the model should be set to this default
	 * @var array
	 */
	protected $optional = ['published'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Domain\Events\EventValidator';

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Domain\Events\EventPresenter';

	/**
	 * A single event belongs to a single event type
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function type()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\EventTypes\EventType', 'event_type_id');
	}

	/**
	 * A single event has many event signups
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\EventSignups\EventSignup');
	}

	/**
	 * Check if the event has a signup from a given user
	 * @param  int		$userId		User's id in the database
	 * @return boolean				True if event has a signup from the specified user, false otherwise
	 */
	public function hasSignupFromUser($userId)
	{
		return ($this->eventSignups()->where('user_id', $userId)->count() > 0);
	}

	/**
	 * Check if the event is open for signups
	 * @return boolean	True if event is open for signups, false otherwise
	 */
	public function isOpenForSignups()
	{
		if( is_null( $this->signup_opens ) ) return false;
		return ( time() < strtotime($this->signup_closes) && time() > strtotime($this->signup_opens) );
	}

	/**
	 * Check if the event allows signups
	 * @return boolean	True if event allows signups, false otherwise
	 */
	public function allowsSignups()
	{
		return ( ! is_null( $this->signup_opens ) );
	}

}
<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Event extends BaseModel {

	protected $fillable = ['name', 'description', 'start', 'end', 'signup_opens', 'signup_closes', 'event_type_id'];
	protected $nullable = ['event_type_id', 'signup_opens', 'signup_closes'];
	
	public $validator = 'Zeropingheroes\Lanager\Events\EventValidator';

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Events\EventPresenter';

	public function type()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\EventTypes\EventType', 'event_type_id');
	}

	public function eventSignups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\EventSignups\EventSignup');
	}

	public function hasSignupFromUser($userId)
	{
		return ($this->eventSignups()->where('user_id', $userId)->count() > 0);
	}

	public function isOpenForSignups()
	{
		return ( time() < strtotime($this->signup_closes) && time() > strtotime($this->signup_opens) );
	}

	public function allowsSignups()
	{
		return ( ! is_null( $this->signup_opens ) );
	}

}
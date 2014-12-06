<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Event extends BaseModel {

	protected $fillable = ['name', 'description', 'start', 'end', 'signup_opens', 'signup_closes', 'event_type_id'];
	protected $nullable = ['event_type_id', 'signup_opens', 'signup_closes'];

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Events\EventPresenter';

	public function type()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Events\Types\Type', 'event_type_id');
	}

	public function signups()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Signups\Signup');
	}

	public function hasSignupFromUser($userId)
	{
		return ($this->signups()->where('user_id', $userId)->count() > 0);
	}

}
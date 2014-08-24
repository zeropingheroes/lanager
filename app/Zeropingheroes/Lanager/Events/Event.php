<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseModel;
use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;

class Event extends BaseModel {

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Events\EventPresenter';

	public static $rules = array(
		'name'			=> 'required|max:255',
		'start'			=> 'required|date_format:Y-m-d H:i:s|before:end',
		'end'			=> 'required|date_format:Y-m-d H:i:s|after:start',
		'signup_opens'	=> 'date_format:Y-m-d H:i:s|before:signup_closes|before:end',
		'signup_closes'	=> 'date_format:Y-m-d H:i:s|after:signup_opens',
		'event_type_id'	=> 'numeric|exists:event_types,id',
	);

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
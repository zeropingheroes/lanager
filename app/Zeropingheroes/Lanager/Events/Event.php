<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseModel;

use Carbon\Carbon;

use Laracasts\Presenter\PresentableTrait;


class Event extends BaseModel {

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Events\EventPresenter';

	public static $rules = array(
		'name'			=> 'required|max:255',
		'start'			=> 'required|date_format:d/m/Y H:i',
		'end'			=> 'required|date_format:d/m/Y H:i|date_not_before_this_input:start',
		'signup_opens'	=> 'date_format:d/m/Y H:i',
		'signup_closes'	=> 'date_format:d/m/Y H:i|date_not_before_this_input:signup_opens',
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

	public function beforeSave()
	{
		// Convert from UK date format
		$this->start = Carbon::createFromFormat('d/m/Y H:i',$this->start);
		$this->end = Carbon::createFromFormat('d/m/Y H:i',$this->end);
		if( $this->signup_opens != NULL )
		{
			$this->signup_opens = Carbon::createFromFormat('d/m/Y H:i',$this->signup_opens);
			$this->signup_closes = Carbon::createFromFormat('d/m/Y H:i',$this->signup_closes);
		}
		else
		{
			$this->signup_opens = NULL;
			$this->signup_closes = NULL;
		}
	}


}
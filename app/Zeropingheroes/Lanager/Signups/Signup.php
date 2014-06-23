<?php namespace Zeropingheroes\Lanager\Signups;

use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\Events\Event;
use Illuminate\Support\MessageBag;
use Auth;

class Signup extends BaseModel {

	protected $table = 'event_signups';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function event()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Events\Event');
	}

	public function beforeSave()
	{
		$errors = new MessageBag;
		$event = Event::findOrFail($this->event_id);
		$thisUsersSignups = Signup::where('event_id', $this->event_id)->where('user_id', $this->user_id);
		if( $thisUsersSignups->count() ) 
		{
			$this->validationErrors = $errors->add('error', 'Already signed up to this event.' );
			return false;
		}
		if( strtotime($event->signup_closes) < time() )
		{
			$this->validationErrors = $errors->add('error', 'Signups for this event have closed.' );
			return false;
		}

	}

}

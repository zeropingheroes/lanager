<?php namespace Zeropingheroes\Lanager\EventSignups;

use Zeropingheroes\Lanager\BaseModel;

class EventSignup extends BaseModel {

	protected $fillable = ['event_id', 'user_id',];
	public $validator = 'Zeropingheroes\Lanager\EventSignups\EventSignupValidator';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function event()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Events\Event');
	}
}

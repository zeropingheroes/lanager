<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\BaseModel;

class EventSignup extends BaseModel {

	protected $fillable = [ 'event_id', 'user_id' ];

	/**
	 * An event signup belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

	/**
	 * An event signup belongs to a single event
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function event()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Events\Event');
	}
}

<?php namespace Zeropingheroes\Lanager\EventSignups;

use Zeropingheroes\Lanager\BaseModel;

class EventSignup extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['event_id', 'user_id',];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\EventSignups\EventSignupValidator';

	/**
	 * An event signup belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	/**
	 * An event signup belongs to a single event
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function event()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Events\Event');
	}
}

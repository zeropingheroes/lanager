<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\BaseModel;

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
	public $validator = 'Zeropingheroes\Lanager\Domain\EventSignups\EventSignupValidator';

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

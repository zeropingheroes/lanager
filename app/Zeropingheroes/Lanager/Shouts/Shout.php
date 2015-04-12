<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\BaseModel;

class Shout extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['user_id', 'content', 'pinned'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Shouts\ShoutValidator';

	/**
	 * A single shout belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
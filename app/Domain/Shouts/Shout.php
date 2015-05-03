<?php namespace Zeropingheroes\Lanager\Domain\Shouts;

use Zeropingheroes\Lanager\Domain\BaseModel;

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
	public $validator = 'Zeropingheroes\Lanager\Domain\Shouts\ShoutValidator';

	/**
	 * A single shout belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

}
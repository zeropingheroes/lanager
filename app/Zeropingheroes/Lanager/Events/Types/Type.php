<?php namespace Zeropingheroes\Lanager\Events\Types;

use Zeropingheroes\Lanager\BaseModel;

class Type extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'event_types';

	protected $fillable = ['name', 'colour'];

	protected $nullable = ['colour'];

	public function event()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Events\Event');
	}

}
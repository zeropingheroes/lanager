<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\BaseModel;

class Shout extends BaseModel {

	protected $fillable = ['user_id', 'content', 'pinned'];

	public $validator = 'Zeropingheroes\Lanager\Shouts\ShoutValidator';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}
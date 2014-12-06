<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Users\User;
use Eloquent;

class BaseModel extends Eloquent {

	protected $nullable = [];

	/**
	* Listen for save event
	*/
	protected static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			self::setNullables($model);
		});
	}

	/**
	* Unset empty nullable fields so that MySQL will null/default them
	* @param object $model
	*/
	protected static function setNullables($model)
	{
		foreach($model->nullable as $field)
		{
			if ( empty($model->{$field}) )
			{
				unset($model->{$field});
			}
		}
	}

	public function scopeOnlyVisibleUsers($query)
	{
		$query->whereIn('user_id', function($query)
		{
			$query->select('id')
					->from(with(new User)->getTable())
					->where('visible', 1);
		});
	}

}
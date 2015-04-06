<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Users\User;
use Eloquent;

class BaseModel extends Eloquent {

	protected $optional = [];
	protected $nullable = [];
	public $validator = '';

	/**
	* Listen for save event
	*/
	protected static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			self::unsetOptionalFields($model);
			self::nullNullableFields($model);
		});
	}

	/**
	* Unset optional fields that are empty, so that MySQL will default them
	* @param object $model
	*/
	protected static function unsetOptionalFields($model)
	{
		foreach($model->optional as $field)
		{
			if ( empty($model->{$field}) )
			{
				unset($model->{$field});
			}
		}
	}

	/**
	* Set nullable fields that are empty/missing to null
	* @param object $model
	*/
	protected static function nullNullableFields($model)
	{
		foreach($model->nullable as $field)
		{
			if ( ! isset($model->{$field} ) OR empty($model->{$field}) )
			{
				$model->{$field} = NULL;
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
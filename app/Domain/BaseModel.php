<?php namespace Zeropingheroes\Lanager\Domain;

use Eloquent;

class BaseModel extends Eloquent
{
    /**
     * Fields that can be mass assigned
     * @var array
     */
    protected $fillable = [];

    /**
     * Fields that have a useful default set in the database
     * If any of these fields are empty when creating or updating the model should be set to this default
     * @var array
     */
    protected $optional = [];

    /**
     * Fields that can be set to null in the database, if they are not specified when creating a new model
     * @var array
     */
    protected $nullable = [];

    /**
     * Presenter class responsible for presenting this model's fields
     * @var string
     */
    protected $presenter = '';

    /**
     * Perform actions when class is instantiated
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            self::unsetOptionalFields($model);
            self::nullNullableFields($model);
        });
    }

    /**
     * Unset optional fields that are empty, so that the database will use the field's default
     * @param object $model
     */
    protected static function unsetOptionalFields($model)
    {
        foreach ($model->optional as $field) {
            if (
                empty($model->{$field})
                && $model->{$field} !== 0
                && $model->{$field} !== '0'
            ) {
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
        foreach ($model->nullable as $field) {
            if (!isset($model->{$field}) OR empty($model->{$field})) {
                $model->{$field} = null;
            }
        }
    }

}
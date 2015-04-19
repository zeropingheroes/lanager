<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseModel;

class Page extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['parent_id', 'title', 'content', 'position', 'published'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['content', 'parent_id', 'position'];

	/**
	 * Fields that have a useful default set in the database
	 * If any of these fields are empty when creating or updating the model should be set to this default
	 * @var array
	 */
	protected $optional = ['published'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Pages\PageValidator';

	/**
	 * Pseudo-relation: A single page may optionally have a signle parent
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function parent()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Pages\Page', 'parent_id');
	}

	/**
	 * Pseudo-relation: A single page may optionally have many children
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function children()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Pages\Page', 'parent_id');
	}

}
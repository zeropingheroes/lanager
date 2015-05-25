<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use Zeropingheroes\Lanager\Domain\BaseModel;

class Page extends BaseModel {

	protected $fillable = [ 'parent_id', 'title', 'content', 'position', 'published' ];

	protected $nullable = [ 'content', 'parent_id', 'position' ];

	protected $optional = [ 'published' ];

	/**
	 * Pseudo-relation: A single page may optionally have a signle parent
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function parent()
	{
		return $this->belongsTo( 'Zeropingheroes\Lanager\Domain\Pages\Page', 'parent_id' );
	}

	/**
	 * Pseudo-relation: A single page may optionally have many children
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function children()
	{
		return $this->hasMany( 'Zeropingheroes\Lanager\Domain\Pages\Page', 'parent_id' );
	}

}
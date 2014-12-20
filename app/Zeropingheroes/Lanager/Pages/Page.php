<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseModel;

class Page extends BaseModel {

	protected $fillable = ['parent_id', 'title', 'content'];
	protected $nullable = ['parent_id', 'content'];

	public $validator = 'Zeropingheroes\Lanager\Pages\PageValidator';

    public function parent()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Pages\Page', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Pages\Page', 'parent_id');
    }

}
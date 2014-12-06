<?php namespace Zeropingheroes\Lanager\InfoPages;

use Zeropingheroes\Lanager\BaseModel;

class InfoPage extends BaseModel {

	protected $fillable = ['parent_id', 'title', 'content'];
	protected $nullable = ['parent_id', 'content'];

	public $validator = 'Zeropingheroes\Lanager\InfoPages\InfoPageValidator';


}
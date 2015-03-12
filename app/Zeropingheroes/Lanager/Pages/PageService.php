<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\FlatResourceService;
use Cache;

class PageService extends FlatResourceService {

	protected $resource = 'page';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Page);
	}

	public function single($id, $eagerLoad = [])
	{
		return $this->model->with('children')->findOrFail($id);
	}

	public function store($input)
	{
		Cache::forget('pageMenu');
		return parent::store($input);
	}

	public function update($id, $input)
	{
		Cache::forget('pageMenu');
		return parent::update($id, $input);
	}
}
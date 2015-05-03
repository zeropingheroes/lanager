<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use Zeropingheroes\Lanager\Domain\FlatResourceService;
use Cache;

class PageService extends FlatResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	protected $resource = 'pages';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		parent::__construct($listener, new Page);
	}

	/**
	 * Get a single resource item (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function single($id, $eagerLoad = [])
	{
		return $this->model->with('children')->findOrFail($id);
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $input raw input from user
	 */
	public function store($input)
	{
		Cache::forget('pageMenu');
		return parent::store($input);
	}

	/**
	 * Update the resource (with additional processing to standard service method)
	 * @param  array  $id   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update($id, $input)
	{
		Cache::forget('pageMenu');
		return parent::update($id, $input);
	}

	/**
	 * Destroy the resource (with additional processing to standard service method)
	 * @param  array  $id   list of ids of parent models
	 */
	public function destroy($id)
	{
		Cache::forget('pageMenu');
		return parent::destroy($id);
	}
}
<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Shouts\ShoutService;
use View, Notification, Redirect;

class ShoutsController extends BaseController {

	protected $route = 'shouts';

	public function __construct()
	{
		parent::__construct();
		$this->service = new ShoutService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$options['orderBy'] = ['-pinned', 'created_at'];

		return View::make('shouts.index')
					->with('title', 'Shouts')
					->with('shouts', $this->service->all($options));
	}

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->model()->id );
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->model()->id );
	}

}
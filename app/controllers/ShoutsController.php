<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Shouts\ShoutService;
use View, Notification, Redirect;

class ShoutsController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'shouts';

	/**
	 * Set the controller's service
	 */
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
		$options['orderBy'] = ['-pinned', '-created_at'];
		$eagerLoad =
		[
			'user.roles',
			'user.state.application',
			'user.state.server',
		];

		$shouts = $this->service->all($options, $eagerLoad);

		return View::make('shouts.index')
					->with('title', 'Shouts')
					->with('shouts', $shouts);
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

}
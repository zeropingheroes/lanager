<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Logs\LogService;
use View;

class LogsController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'logs';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new logService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('logs.index')
					->with('title','Logs')
					->with('logs', $this->service->all(['orderBy' => '-created_at']));
	}
}
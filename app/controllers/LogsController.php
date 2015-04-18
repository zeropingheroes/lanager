<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Logs\LogService;
use Input, View;

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
		$minLevel = Input::get('minLevel');

		$levels = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];

		$levelsToShow = array_slice( $levels, array_search($minLevel, $levels) );

		$filters['orderBy'] = '-created_at';
		$filters['level'] = $levelsToShow;
		if( Input::get('sapi') ) $filters['php_sapi_name'] = Input::get('sapi');

		return View::make('logs.index')
					->with('title','Logs')
					->with('logs', $this->service->all( $filters ));
	}
}
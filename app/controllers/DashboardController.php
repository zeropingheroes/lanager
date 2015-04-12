<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Events\EventService;
use Zeropingheroes\Lanager\NotificationListener;
use Illuminate\Routing\Controller;
use View;

class DashboardController extends Controller {

	/**
	 * Event service
	 * @var object EventService
	 */
	private $events;

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->events = new EventService( new NotificationListener );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('dashboard.index')
					->with('title','Live Dashboard');
	}

}
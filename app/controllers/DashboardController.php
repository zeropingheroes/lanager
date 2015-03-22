<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Events\EventService;
use Zeropingheroes\Lanager\NotificationListener;
use Illuminate\Routing\Controller;
use View;

class DashboardController extends Controller {

	private $events;

	public function __construct()
	{
		$this->events = new EventService( new NotificationListener );
	}

	public function index()
	{
		return View::make('dashboard.index')
					->with('title','Live Dashboard');
	}

}
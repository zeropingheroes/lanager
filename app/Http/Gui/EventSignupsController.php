<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use View;

class EventSignupsController extends ResourceServiceController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'events.signups';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new EventSignupService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($eventId)
	{
		
		$eagerLoad =
		[
			'user.state.application',
			'user.state.server',
		];
	
		$eventSignups = $this->service->all([$eventId], [], $eagerLoad);
		$event = $this->service->parent([$eventId]);

		return View::make('event-signups.index')
					->with('title','Signups: '.$event->name)
					->with('event',$event)
					->with('eventSignups', $eventSignups);
	}

}
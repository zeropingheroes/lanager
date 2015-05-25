<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use View;
use Redirect;

class EventSignupsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new EventSignupService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( $eventId )
	{
		$eventSignups = $this->service->all( $eventId );

		$event = (new EventService)->single( $eventId );

		return View::make('event-signups.index')
					->with('title','Signups: '.$event->name)
					->with('event',$event)
					->with('eventSignupService',$this->service)
					->with('eventSignups', $eventSignups);
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('events.signups.index', $this->currentRouteParameters() );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return $this->redirectAfterStore();
	}

}
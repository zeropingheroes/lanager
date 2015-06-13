<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use View;
use Redirect;
use Input;

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
		$eventSignups = $this->service->filterByEvent( $eventId )->all();

		$event = (new EventService)->single( $eventId );

		return View::make('event-signups.index')
					->with('title','Signups: '.$event->name)
					->with('event',$event)
					->with('eventSignupService',$this->service)
					->with('eventSignups', $eventSignups);
	}

	public function store()
	{
		$input = Input::all();
		$input['event_id'] = func_get_arg(0);

		return parent::processStore( $input );
	}

	public function update()
	{
		$this->service = $this->service->filterByEvent( func_get_arg(0) );

		return parent::processUpdate( func_get_arg(1), Input::get() );
	}

	public function destroy()
	{
		$this->service = $this->service->filterByEvent( func_get_arg(0) );

		return parent::processDestroy( func_get_arg(1) );
	}

	protected function redirectAfterStore( $resource )
	{
		return Redirect::route('events.signups.index', $this->currentRouteParameters() );
	}

	protected function redirectAfterUpdate( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

	protected function redirectAfterDestroy( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

}
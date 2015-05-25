<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use	Zeropingheroes\Lanager\Domain\EventSignups\EventSignupTransformer;

class EventSignupsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new EventSignupService;
		$this->transformer = new EventSignupTransformer;
	}

	public function index( $eventId )
	{
		$items = $this->service->filterByEvent( $eventId )->all();

		return $this->response->collection( $items, $this->transformer );
	}

	public function show( $eventId, $eventSignupId )
	{
		$item = $this->service->filterByEvent( $eventId )->single( $eventSignupId );

		return $this->response->item( $item, $this->transformer );
	}

}
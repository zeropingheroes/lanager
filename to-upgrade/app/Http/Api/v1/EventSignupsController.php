<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Input;
use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupTransformer;

class EventSignupsController extends ResourceServiceController
{

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new EventSignupService;
        $this->transformer = new EventSignupTransformer;
    }

    public function index()
    {
        if (Input::has('event_id')) {
            $this->service->filterByEvent(Input::get('event_id'));
        }

        if (Input::has('user_id')) {
            $this->service->filterByUser(Input::get('user_id'));
        }

        $items = $this->service->all();

        return $this->response->collection($items, $this->transformer);
    }

    public function show($id)
    {
        $item = $this->service->single($id);

        return $this->response->item($item, $this->transformer);
    }

}
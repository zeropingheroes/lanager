<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Shouts\ShoutService;
use Zeropingheroes\Lanager\Domain\Shouts\ShoutTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class ShoutsController extends ResourceServiceController
{

    use FlatResourceTrait;

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new ShoutService;
        $this->transformer = new ShoutTransformer;
    }

}
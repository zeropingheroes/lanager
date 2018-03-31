<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Users\UserService;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class UsersController extends ResourceServiceController
{

    use FlatResourceTrait;

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService;
        $this->transformer = new UserTransformer;
    }

}
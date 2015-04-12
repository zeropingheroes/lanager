<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Users\UserTransformer;

class UsersController extends BaseController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new UserService($this);
		$this->transformer = new UserTransformer;
	}

}
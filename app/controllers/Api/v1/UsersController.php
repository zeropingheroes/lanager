<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Users\UserTransformer;

class UsersController extends BaseController {

	public function __construct()
	{
		$this->service = new UserService($this);
		$this->transformer = new UserTransformer;
	}

}
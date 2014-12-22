<?php namespace Zeropingheroes\Lanager\Api;

use Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Users\UserTransformer;

class UsersController extends BaseController {

	public function __construct()
	{
		$this->service = new UserService($this);
		$this->transformer = new UserTransformer;
	}

}
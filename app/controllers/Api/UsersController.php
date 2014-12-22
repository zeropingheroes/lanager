<?php namespace Zeropingheroes\Lanager\Api;

use Zeropingheroes\Lanager\Users\UserService;

class UsersController extends BaseController {

	protected $resourceTransformer = 'Zeropingheroes\Lanager\Users\UserTransformer';
	
	public function __construct()
	{
		$this->service = new UserService($this);
	}

}
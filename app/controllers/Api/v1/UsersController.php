<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Users\UserTransformer;

class UsersController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new UserService($this);
		$this->transformer = new UserTransformer;
	}

	public function store()
	{
		return $this->response->error('Method not allowed', 405);
	}

	public function update($id)
	{
		return $this->response->error('Method not allowed', 405);
	}
}
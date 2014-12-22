<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class UserService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'user';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new User;
	}

	public function all()
	{
		return $this->model->visible()->orderBy('username', 'asc')->get();
	}

	public function single($id)
	{
		return $this->model->visible()->findOrFail($id);
	}
}
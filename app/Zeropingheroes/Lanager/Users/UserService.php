<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class UserService extends BaseResourceService implements ResourceServiceContract {

	public $model = 'Zeropingheroes\Lanager\Users\User';

	public $resourceName = 'user';

	public function all()
	{
		$users = call_user_func($this->model . '::visible'); // only show visible users
		return $users->orderBy('username', 'asc')->get();
	}

	public function single($id)
	{
		$users = call_user_func($this->model . '::visible'); // only show visible users
		return $users->findOrFail($id);
	}
}
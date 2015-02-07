<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\SingularResourceService;

class UserService extends SingularResourceService {

	protected $resource = 'user';

	public function __construct( $listener )
	{
		parent::__construct($listener, new User);
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
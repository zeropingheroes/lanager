<?php namespace Zeropingheroes\Lanager\Users;

use Zeropingheroes\Lanager\FlatResourceService;

class UserService extends FlatResourceService {

	protected $resource = 'user';

	public function __construct( $listener )
	{
		parent::__construct($listener, new User);
	}

	// Todo: convert to using global scopes: http://laravel.com/docs/4.2/eloquent#global-scopes
	// public function all()
	// {
	// 	return $this->model->visible()->orderBy('username', 'asc')->get();
	// }

	// public function single($id)
	// {
	// 	return $this->model->visible()->findOrFail($id);
	// }
}
<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\UserRoles\UserRoleService;
use Zeropingheroes\Lanager\Domain\Users\UserService;
use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use View;

class UserRolesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new UserRoleService;
		$this->users 	= lists( (new UserService)->all(), 'id', 'username' );
		$this->roles 	= lists( (new RoleService)->all(), 'id', 'name' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userRoles = $this->service->all();

		return View::make( 'user-roles.index' )
					->with( 'title', 'User Roles' )
					->with( 'userRoles', $userRoles );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make( 'user-roles.create' )
					->with( 'title', 'Assign Role' )
					->with( 'users', $this->users )
					->with( 'roles', $this->roles )
					->with( 'userRole', null );
	}

	protected function redirectAfterStore()
	{
		return Redirect::route( 'user-roles.index' );
	}

	protected function redirectAfterDestroy()
	{
		return $this->redirectAfterStore();
	}

}
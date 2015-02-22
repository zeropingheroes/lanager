<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\UserRoles\UserRoleService,
	Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Roles\RoleService;
use Zeropingheroes\Lanager\NotificationListener;
use View, Notification, Redirect;

class UserRolesController extends BaseController {

	protected $route = 'user-roles';

	public function __construct()
	{
		parent::__construct();
		$this->service = new UserRoleService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('user-roles.index')
					->with('title','User Roles')
					->with('userRoles', $this->service->all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$users = (new UserService((new NotificationListener)))->lists('username', 'id');
		$roles = (new RoleService((new NotificationListener)))->lists('name', 'id');

		return View::make('user-roles.create')
					->with('title','Assign Role')
					->with('users',$users)
					->with('roles',$roles)
					->with('userRole',null);
	}

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}
}
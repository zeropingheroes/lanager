<?php namespace Zeropingheroes\Lanager\Gui;

use Zeropingheroes\Lanager\BaseResourceService;
use Zeropingheroes\Lanager\UserRoles\UserRoleService,
	Zeropingheroes\Lanager\Users\UserService,
	Zeropingheroes\Lanager\Roles\RoleService;
use Zeropingheroes\Lanager\NotificationListener;
use View, Notification, Redirect;

class UserRolesController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'user-roles';

	/**
	 * Set the controller's service
	 */
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
		$eagerLoad =
		[
			'role',
			'user.state.application',
			'user.state.server',
		];
		$userRoles = $this->service->all( [], $eagerLoad);

		return View::make('user-roles.index')
					->with('title','User Roles')
					->with('userRoles', $userRoles);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$users = (new UserService((new NotificationListener)))->lists('username', 'id', 'username');
		$roles = (new RoleService((new NotificationListener)))->lists('name', 'id', 'name');

		return View::make('user-roles.create')
					->with('title','Assign Role')
					->with('users',$users)
					->with('roles',$roles)
					->with('userRole',null);
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}
}
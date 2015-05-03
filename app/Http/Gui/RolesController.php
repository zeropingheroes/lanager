<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use View;
use Notification;
use Redirect;

class RolesController extends ResourceServiceController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'roles';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new RoleService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eagerLoad = ['userRoles', 'users'];
		$roles = $this->service->all([], $eagerLoad);

		return View::make('roles.index')
					->with('title','Roles')
					->with('roles', $roles);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('roles.create')
					->with('title','Create Role')
					->with('role',null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$eagerLoad =
		[
			'userRoles.role',
			'userRoles.user.state.application',
			'userRoles.user.state.server',
		];
		$role = $this->service->single($id, $eagerLoad);

		return View::make('roles.show')
					->with('title', 'Role: '.$role->name)
					->with('role',$role);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('roles.edit')
					->with('title','Edit Role')
					->with('role',$this->service->single($id));
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

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}

}
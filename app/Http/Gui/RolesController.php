<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use View;
use Redirect;

class RolesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new RoleService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$roles = $this->service->all();

		return View::make( 'roles.index' )
					->with( 'title', 'Roles' )
					->with( 'roles', $roles );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make( 'roles.create' )
					->with( 'title', 'Create Role' )
					->with( 'role', null );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show( $id )
	{
		$role = $this->service->single( $id );

		return View::make( 'roles.show' )
					->with( 'title', 'Role: '.$role->name)
					->with( 'role', $role );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit( $id )
	{
		return View::make( 'roles.edit' )
					->with( 'title', 'Edit Role' )
					->with( 'role', $this->service->single( $id ) );
	}

	protected function redirectAfterStore( $resource )
	{
		return Redirect::route('roles.show', $resource['id'] );
	}

	protected function redirectAfterUpdate( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

	protected function redirectAfterDestroy( $resource )
	{
		return Redirect::route('roles.index');
	}

}
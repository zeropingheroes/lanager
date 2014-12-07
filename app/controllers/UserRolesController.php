<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\UserRoles\UserRole,
	Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\Roles\Role;
use View, Input, Redirect;

class UserRolesController extends BaseController {
	
	public function __construct()
	{
		$this->beforeFilter('permission',['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy'] ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userRoles = UserRole::with(['role','user'])->get();

		return View::make('user-roles.index')
					->with('title','User Roles')
					->with('userRoles',$userRoles);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$userRole = new UserRole;
		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$roles = Role::orderBy('name')
						->lists('name','id');

		return View::make('user-roles.create')
					->with('title','Assign Role')
					->with('users',$users)
					->with('roles',$roles)
					->with('userRole',$userRole);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$userRole = new UserRole;
		$userRole->fill( Input::get() );

		if ( ! $this->save($userRole) ) return Redirect::back()->withInput();

		return Redirect::route('user-roles.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$userRole = UserRole::findOrFail($id);
		$this->delete($userRole);
		return Redirect::back();
	}

}
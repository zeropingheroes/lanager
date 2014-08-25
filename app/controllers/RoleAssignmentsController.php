<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\RoleAssignments\RoleAssignment,
	Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\Roles\Role;
use View, Input, Redirect, Request, Response;

class RoleAssignmentsController extends BaseController {
	
	public function __construct()
	{
		$this->beforeFilter('permission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$roleAssignments = RoleAssignment::with(array('role','user'))->get();

		if ( Request::ajax() ) return Response::json($roleAssignments);

		return View::make('roleassignments.index')
					->with('title','Role Assignments')
					->with('roleAssignments',$roleAssignments);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$roleAssignment = new RoleAssignment;
		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$roles = Role::orderBy('name')
						->lists('name','id');

		return View::make('roleassignments.create')
					->with('title','Assign Role')
					->with('users',$users)
					->with('roles',$roles)
					->with('roleAssignment',$roleAssignment);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$roleAssignment = new RoleAssignment;

		$roleAssignment->user_id	= User::findOrFail(Input::get('user_id'))->id;
		$roleAssignment->role_id	= Role::findOrFail(Input::get('role_id'))->id;
		
		return $this->process( $roleAssignment, 'role-assignments.index' );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$roleAssignment = RoleAssignment::onlyVisibleUsers()->with(array('role','user'))->findOrFail($id);
		
		if ( Request::ajax() ) return Response::json($roleAssignment);
		
		return Redirect::route('role-assignments.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$roleAssignment = RoleAssignment::findOrFail($id);

		if( Input::has('user_id') )	$roleAssignment->user_id	= User::findOrFail(Input::get('user_id'))->id;
		if( Input::has('role_id') )	$roleAssignment->role_id	= Role::findOrFail(Input::get('role_id'))->id;

		return $this->process( $roleAssignment );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$roleAssignment = RoleAssignment::findOrFail($id);

		return $this->process( $roleAssignment );
	}

}
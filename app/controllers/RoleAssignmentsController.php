<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\RoleAssignments\RoleAssignment,
	Zeropingheroes\Lanager\RoleAssignments\RoleAssignmentValidator,
	Zeropingheroes\Lanager\Users\User,
	Zeropingheroes\Lanager\Roles\Role;
use View, Input, Redirect, Notification;

class RoleAssignmentsController extends BaseController {
	
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
		$roleAssignments = RoleAssignment::with(['role','user'])->get();

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
		$roleAssignment->fill( Input::get() );

		$roleAssignmentValidator = RoleAssignmentValidator::make( $roleAssignment->toArray() )->scope('store');

		if ( $roleAssignmentValidator->fails() )
		{
			Notification::danger( $roleAssignmentValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$roleAssignment->save();
		Notification::success( trans('confirmation.after.resource.store', ['resource' => 'role assignment']) );
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
		$roleAssignment = RoleAssignment::findOrFail($id);

		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$roles = Role::orderBy('name')
						->lists('name','id');

		return View::make('roleassignments.edit')
					->with('title','Edit Role Assignment')
					->with('users',$users)
					->with('roles',$roles)
					->with('roleAssignment',$roleAssignment);
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
		$roleAssignment->fill( Input::get() );
		
		$roleAssignmentValidator = RoleAssignmentValidator::make( $roleAssignment->toArray() )->scope('update');

		if ( $roleAssignmentValidator->fails() )
		{
			Notification::danger( $roleAssignmentValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$roleAssignment->save();
		Notification::success( trans('confirmation.after.resource.update', ['resource' => 'role assignment']) );
		return Redirect::route('role-assignments.index');
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

		$roleAssignment->delete();
		Notification::success( trans('confirmation.after.resource.destroy', ['resource' => 'role assignment']) );
		return Redirect::back();
	}

}
<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\Http\Requests\StoreRoleAssignment;
use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\User;
use Illuminate\Support\Facades\View;

class RoleAssignmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pages.role-assignment.index')
            ->with('roleAssignments', RoleAssignment::all())
            ->with('users', User::all()->pluck('username', 'id'))
            ->with('roles', Role::all()->pluck('name', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreRoleAssignment $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleAssignment $request)
    {
        $input = $request->validated();

        RoleAssignment::create($input);

        return redirect()
            ->route('role-assignments.index')
            ->with('alerts', [
                [
                    'message' => __('phrase.role-successfully-assigned'),
                    'type' => 'success'
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\RoleAssignment $roleAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleAssignment $roleAssignment)
    {
        //
    }
}

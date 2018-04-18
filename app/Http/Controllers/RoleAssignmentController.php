<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Requests\DestroyRoleAssignmentRequest;
use Zeropingheroes\Lanager\Requests\StoreRoleAssignmentRequest;
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
            ->with('users', User::orderBy('username')->get()->pluck('username', 'id'))
            ->with('roles', Role::all()->pluck('name', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request|StoreRoleAssignmentRequest $request
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', RoleAssignment::class);

        $input = $httpRequest->only(['user_id', 'role_id']);

        $request = new StoreRoleAssignmentRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withErrors($request->errors())
                ->withInput();
        }
        $input['assigned_by'] = Auth::user()->id;

        $roleAssignment = RoleAssignment::create($input);

        return redirect()
            ->route('role-assignments.index')
            ->with('alerts', [
                [
                    'message' => __('phrase.role-successfully-assigned', ['user' => $roleAssignment->user->username, 'role' => $roleAssignment->role->name]),
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
        $this->authorize('delete', RoleAssignment::class);

        $request = new DestroyRoleAssignmentRequest(['id' => $roleAssignment->id]);
        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withErrors($request->errors())
                ->withInput();
        }

        RoleAssignment::destroy($roleAssignment->id);

        return redirect()
            ->route('role-assignments.index')
            ->with('alerts', [
                [
                    'message' => __('phrase.role-successfully-unassigned', ['user' => $roleAssignment->user->username, 'role' => $roleAssignment->role->name]),
                    'type' => 'success'
                ]
            ]);
    }
}

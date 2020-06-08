<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use View;
use Zeropingheroes\Lanager\Requests\DestroyRoleAssignmentRequest;
use Zeropingheroes\Lanager\Requests\StoreRoleAssignmentRequest;
use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\User;

class RoleAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', RoleAssignment::class);

        $roleAssignments = RoleAssignment::with('user', 'role')->get();
        $users = User::orderBy('username')->get();
        $roles = Role::all();

        return View::make('pages.role-assignments.index')
            ->with('roleAssignments', $roleAssignments)
            ->with('users', $users)
            ->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', RoleAssignment::class);

        $input = $httpRequest->only(['user_id', 'role_id']);

        $request = new StoreRoleAssignmentRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        $input['assigned_by'] = Auth::user()->id;

        $roleAssignment = RoleAssignment::create($input);

        Session::flash('success',
            trans('phrase.role-successfully-assigned',
                ['user' => $roleAssignment->user->username, 'role' => $roleAssignment->role->display_name]
            )
           );

        return redirect()->route('role-assignments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RoleAssignment $roleAssignment
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(RoleAssignment $roleAssignment)
    {
        $this->authorize('delete', $roleAssignment);

        $request = new DestroyRoleAssignmentRequest(['id' => $roleAssignment->id]);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        RoleAssignment::destroy($roleAssignment->id);

        Session::flash(
            'success',
            trans('phrase.role-successfully-unassigned',
                ['user' => $roleAssignment->user->username, 'role' => $roleAssignment->role->display_name]
            )
           );

        return redirect()->route('role-assignments.index');
    }
}

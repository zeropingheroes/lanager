<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['user_id', 'role_id']);

        $rules = [
            'user_id' => 'exists:users,id',
            'role_id' => 'exists:roles,id',
        ];

        $messages = [
            'unique' => __('phrase.user-already-has-role'),
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

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

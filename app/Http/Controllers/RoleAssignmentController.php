<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\RoleAssignment;
use Illuminate\Http\Request;
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
        $roleAssignments = RoleAssignment::all();
        return View::make('pages.role-assignment.index')
            ->with('roleAssignments', $roleAssignments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\RoleAssignment  $roleAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleAssignment $roleAssignment)
    {
        //
    }
}

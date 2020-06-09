<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use View;
use Zeropingheroes\Lanager\Lan;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Lan $lan)
    {
        $users = $lan->users()->orderBy('username')->get();

        return View::make('pages.users.index')
            ->with('lan', $lan)
            ->with('users', $users);
    }
}

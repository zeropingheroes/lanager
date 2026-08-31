<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Lan;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lan $lan): ViewContract
    {
        $this->authorize('view', $lan);

        $users = $lan->users()->orderBy('username')->get();

        return View::make('pages.users.index')
            ->with('lan', $lan)
            ->with('users', $users);
    }
}

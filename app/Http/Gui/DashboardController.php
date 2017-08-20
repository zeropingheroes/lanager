<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Illuminate\Routing\Controller;
use View;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('dashboard.index')
            ->with('title', 'Live Dashboard');
    }

}
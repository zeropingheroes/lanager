<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Log;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Log::class);

        return View::make('pages.log.index')
            ->with('logs', Log::orderBy('created_at', 'desc')->get());

    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }
}

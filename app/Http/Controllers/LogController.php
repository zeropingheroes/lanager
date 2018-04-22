<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Log;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Log::class);

        $logs = Log::with('user')
            ->filter($request->all())
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return View::make('pages.log.index')
            ->with('logs', $logs);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        return View::make('pages.log.show')
            ->with('log', $log);
    }
}

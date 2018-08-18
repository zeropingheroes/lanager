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
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('view', Log::class);

        $logs = Log::with('user')
            ->filter($request->all())
            ->orderBy('created_at', 'desc')
            ->paginateFilter(15);

        return View::make('pages.logs.index')
            ->with('logs', $logs);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Log $log
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Log $log)
    {
        $this->authorize('view', $log);

        $log->read = true;
        $log->save();
        return View::make('pages.logs.show')
            ->with('log', $log);
    }

    /**
     * Update a collection of log items
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function patch(Request $request)
    {
        $logs = $request->input('logs');
        foreach ($logs as $logId => $input) {
            $log = Log::findOrFail($logId);
            $this->authorize('update', $log);
            $log->update($input);
        }
        return redirect()
            ->route('logs.index')
            ->withSuccess(__('phrase.log-entries-marked-as-read'));
    }
}

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
            ->paginateFilter(15);

        return View::make('pages.log.index')
            ->with('logs', $logs);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Log $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        $log->read = true;
        $log->save();
        return View::make('pages.log.show')
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
        $this->authorize('update', Log::class);

        $logs = $request->input('logs');
        foreach ($logs as $logId => $input) {
            Log::findOrFail($logId)->update($input);
        }
        return redirect()
            ->route('logs.index')
            ->with(
                'alerts',
                [
                    [
                        'message' => __('phrase.log-entries-marked-as-read'),
                        'type' => 'success'
                    ]
                ]
            );

    }
}

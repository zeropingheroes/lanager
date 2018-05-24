<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Lan;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreLanRequest;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pages.lans.index')
            ->with('lans', Lan::visible()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('pages.lans.create')
            ->with('lan', new Lan);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Http\Response
     * @internal param \Zeropingheroes\Lanager\Log $log
     */
    public function show(Lan $lan)
    {
        $lan->load(
            [
                'users' => function ($query) {
                    $query->orderBy('lan_attendees.created_at');
                },
                'users.state',
            ]
        );

        return View::make('pages.lans.show')
            ->with('lan', $lan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Lan::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $lan = Lan::create($input);

        return redirect()
            ->route('lans.index')
            ->withSuccess(__('phrase.lan-successfully-created', ['name' => $lan->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Lan  $lan
     * @return \Illuminate\Http\Response
     */
    public function edit(Lan $lan)
    {
        $this->authorize('update', $lan);

        return View::make('pages.lans.edit')
            ->with('lan', $lan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Request $httpRequest, Lan $lan)
    {
        $this->authorize('update', $lan);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $lan->update($input);

        return redirect()
            ->route('lans.index')
            ->withSuccess(__('phrase.lan-successfully-updated', ['name' => $lan->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\Lan  $lan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan)
    {
        $this->authorize('delete', $lan);

        Lan::destroy($lan->id);

        return redirect()
            ->route('lans.index')
            ->withSuccess(__('phrase.lan-successfully-deleted', ['name' => $lan->name]));

    }
}

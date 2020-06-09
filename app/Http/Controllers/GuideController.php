<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use Str;
use View;
use Zeropingheroes\Lanager\Guide;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreGuideRequest;

class GuideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Lan $lan)
    {
        $guides = $lan->guides()
            ->orderBy('title', 'asc')
            ->get();

        return View::make('pages.guides.index')
            ->with('lan', $lan)
            ->with('guides', $guides);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Lan $lan)
    {
        return View::make('pages.guides.create')
            ->with('lan', $lan)
            ->with('guide', new Guide());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', Guide::class);

        $input = [
            'lan_id' => $lan->id,
            'title' => $httpRequest->input('title'),
            'content' => $httpRequest->input('content'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreGuideRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $guide = Guide::create($input);

        return redirect()
            ->route('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param Guide $guide
     * @param string $slug
     * @return \Illuminate\Contracts\View\View|RedirectResponse
     * @throws AuthorizationException
     */
    public function show(Lan $lan, Guide $guide, $slug = '')
    {
        $this->authorize('view', $guide);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        // If the guide is accessed without the URL slug
        // or an incorrect slug
        // redirect to the guide with the right slug
        if (! $slug || $slug != Str::slug($guide->title)) {
            return redirect()->route(
                'lans.guides.show',
                ['lan' => $guide->lan_id, 'guide' => $guide, 'slug' => Str::slug($guide->title)]
            );
        }

        return View::make('pages.guides.show')
            ->with('lan', $lan)
            ->with('guide', $guide);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lan $lan
     * @param Guide $guide
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, Guide $guide)
    {
        $this->authorize('update', $guide);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        $lans = Lan::orderBy('start', 'desc')->get();

        return View::make('pages.guides.edit')
            ->with('lans', $lans)
            ->with('guide', $guide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @param Guide $guide
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan, Guide $guide)
    {
        $this->authorize('update', $guide);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        $input = [
            'lan_id' => $lan->id,
            'title' => $httpRequest->input('title'),
            'content' => $httpRequest->input('content'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreGuideRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $guide->update($input);

        return redirect()
            ->route('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param Guide $guide
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Guide $guide)
    {
        $this->authorize('delete', $guide);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        Guide::destroy($guide->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.guide'), 'name' => $guide->title])
        );

        return redirect()->route('lans.guides.index', ['lan' => $lan]);
    }
}

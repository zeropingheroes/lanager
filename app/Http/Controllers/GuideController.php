<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Guide;
use Illuminate\Http\Request;
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
            ->visible()
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
            ->with('guide', new Guide);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @return \Illuminate\Http\Response
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
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $guide = Guide::create($input);

        return redirect()
            ->route('lans.guides.index', ['lan' => $lan])
            ->withSuccess(__('phrase.guide-successfully-created', ['title' => $guide->title]));
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Guide $guide
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Lan $lan, Guide $guide, $slug = '')
    {
        // Show 404 if guide is not published
        $guide = Guide::visible()->findOrFail($guide->id);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        // If the guide is accessed without the URL slug
        // or an incorrect slug
        // redirect to the guide with the right slug
        if (!$slug || $slug != str_slug($guide->title)) {
            return redirect()->route('lans.guides.show', ['lan' => $guide->lan_id, 'guide' => $guide, 'slug' => str_slug($guide->title)]);
        }

        return View::make('pages.guides.show')
            ->with('lan', $lan)
            ->with('guide', $guide);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Guide $guide
     * @return \Illuminate\Contracts\View\View
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
     * @param  \Illuminate\Http\Request $httpRequest
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Guide $guide
     * @return \Illuminate\Http\Response
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
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $guide->update($input);

        return redirect()
            ->route('lans.guides.show', ['lan' => $lan, 'guide' => $guide])
            ->withSuccess(__('phrase.guide-successfully-updated', ['title' => $guide->title]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Guide $guide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, Guide $guide)
    {
        $this->authorize('delete', $guide);

        // If the guide is accessed via the wrong LAN ID, show 404
        if ($guide->lan_id != $lan->id) {
            abort(404);
        }

        Guide::destroy($guide->id);

        return redirect()
            ->route('lans.guides.index', ['lan' => $lan])
            ->withSuccess(__('phrase.guide-successfully-deleted', ['title' => $guide->title]));
    }
}

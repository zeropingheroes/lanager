<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\NavigationLink;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreNavigationLinkRequest;

class NavigationLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', NavigationLink::class);

        $navigationLinks = NavigationLink::whereNull('parent_id')
            ->with('children')
            ->orderBy('position')
            ->get();

        return View::make('pages.navigation-links.index')
            ->with('navigationLinks', $navigationLinks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', NavigationLink::class);

        $navigationLinks = NavigationLink::whereNull('parent_id')
            ->get();

        return View::make('pages.navigation-links.create')
            ->with('navigationLinks', $navigationLinks)
            ->with('navigationLink', new NavigationLink());
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
        $this->authorize('create', NavigationLink::class);

        $input = [
            'title' => $httpRequest->input('title'),
            'url' => $httpRequest->input('url'),
            'position' => $httpRequest->input('position'),
            'parent_id' => $httpRequest->input('parent_id'),
        ];

        $request = new StoreNavigationLinkRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $navigationLink = NavigationLink::create($input);

        return redirect()
            ->route('navigation-links.index')
            ->withSuccess(__('phrase.navigation-link-successfully-created', ['title' => $navigationLink->title]));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function edit(NavigationLink $navigationLink)
    {
        $this->authorize('update', $navigationLink);

        $navigationLinks = NavigationLink::whereNull('parent_id')
            ->where('id', '<>', $navigationLink->id)
            ->get();

        return View::make('pages.navigation-links.edit')
            ->with('navigationLinks', $navigationLinks)
            ->with('navigationLink', $navigationLink);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Request $httpRequest, NavigationLink $navigationLink)
    {
        $this->authorize('update', $navigationLink);

        $input = [
            'title' => $httpRequest->input('title'),
            'url' => $httpRequest->input('url'),
            'position' => $httpRequest->input('position'),
            'parent_id' => $httpRequest->input('parent_id'),
            'id' => $navigationLink->id,
        ];

        $request = new StoreNavigationLinkRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $navigationLink->update($input);

        return redirect()
            ->route('navigation-links.index')
            ->withSuccess(__('phrase.navigation-link-successfully-updated', ['title' => $navigationLink->title]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(NavigationLink $navigationLink)
    {
        $this->authorize('delete', $navigationLink);

        NavigationLink::destroy($navigationLink->id);

        return redirect()
            ->route('navigation-links.index')
            ->withSuccess(__('phrase.guide-successfully-deleted', ['title' => $navigationLink->title]));
    }
}

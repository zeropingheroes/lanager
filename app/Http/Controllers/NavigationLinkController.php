<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\NavigationLink;
use Zeropingheroes\Lanager\Requests\StoreNavigationLinkRequest;

class NavigationLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(): ViewContract
    {
        $this->authorize('index', NavigationLink::class);

        $navigationLinks = NavigationLink::whereNull('parent_id')
            ->orderBy('position')
            ->get();

        return View::make('pages.navigation-links.index')
            ->with('navigationLinks', $navigationLinks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     */
    public function create(): ViewContract
    {
        $this->authorize('create', NavigationLink::class);

        $navigationLinks = NavigationLink::whereNull('parent_id')
            ->get();

        return View::make('pages.navigation-links.create')
            ->with('navigationLinks', $navigationLinks)
            ->with('navigationLink', new NavigationLink);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $this->authorize('create', NavigationLink::class);

        $input = [
            'title' => $httpRequest->input('title'),
            'url' => $httpRequest->input('url'),
            'position' => $httpRequest->input('position'),
            'parent_id' => $httpRequest->input('parent_id'),
        ];

        $storeNavigationLinkRequest = new StoreNavigationLinkRequest($input);

        if ($storeNavigationLinkRequest->invalid()) {
            Session::flash('error', $storeNavigationLinkRequest->errors());

            return redirect()->back()->withInput();
        }

        NavigationLink::create($input);

        return redirect()->route('navigation-links.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(NavigationLink $navigationLink): ViewContract
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
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, NavigationLink $navigationLink): RedirectResponse
    {
        $this->authorize('update', $navigationLink);

        $input = [
            'title' => $httpRequest->input('title'),
            'url' => $httpRequest->input('url'),
            'position' => $httpRequest->input('position'),
            'parent_id' => $httpRequest->input('parent_id'),
            'id' => $navigationLink->id,
        ];

        $storeNavigationLinkRequest = new StoreNavigationLinkRequest($input);

        if ($storeNavigationLinkRequest->invalid()) {
            Session::flash('error', $storeNavigationLinkRequest->errors());

            return redirect()->back()->withInput();
        }

        $navigationLink->update($input);

        return redirect()
            ->route('navigation-links.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(NavigationLink $navigationLink): RedirectResponse
    {
        $this->authorize('delete', $navigationLink);

        NavigationLink::destroy($navigationLink->id);

        Session::flash(
            'success',
            trans(
                'phrase.item-name-deleted',
                ['item' => trans('title.navigation-link'), 'name' => $navigationLink->title]
            )
        );

        return redirect()->route('navigation-links.index');
    }
}

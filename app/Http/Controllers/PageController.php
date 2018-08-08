<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Page;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StorePageRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Lan $lan)
    {
        $pages = $lan->pages()
            ->visible()
            ->orderBy('title', 'asc')
            ->get();

        return View::make('pages.pages.index')
            ->with('lan', $lan)
            ->with('pages', $pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Lan $lan)
    {
        return View::make('pages.pages.create')
            ->with('lan', $lan)
            ->with('page', new Page);
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
        $this->authorize('create', Page::class);

        $input = [
            'lan_id' => $lan->id,
            'title' => $httpRequest->input('title'),
            'content' => $httpRequest->input('content'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StorePageRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $page = Page::create($input);

        return redirect()
            ->route('lans.pages.index', ['lan' => $lan])
            ->withSuccess(__('phrase.page-successfully-created', ['title' => $page->title]));
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Page $page
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Lan $lan, Page $page, $slug = '')
    {
        // Show 404 if page is not published
        $page = Page::visible()->findOrFail($page->id);

        // If the page is accessed via the wrong LAN ID, show 404
        if($page->lan_id != $lan->id) {
            abort(404);
        }

        // If the page is accessed without the URL slug
        // or an incorrect slug
        // redirect to the page with the right slug
        if (!$slug || $slug != str_slug($page->title)) {
            return redirect()->route('lans.pages.show', ['lan' => $page->lan_id, 'page' => $page, 'slug' => str_slug($page->title)]);
        }

        // Get the LAN happening now, or the most recently ended LAN
        $currentLan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        return View::make('pages.pages.show')
            ->with('currentLan', $currentLan)
            ->with('lan', $lan)
            ->with('page', $page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Page $page
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Lan $lan, Page $page)
    {
        $this->authorize('update', $page);

        // If the page is accessed via the wrong LAN ID, show 404
        if($page->lan_id != $lan->id) {
            abort(404);
        }

        $lans = Lan::orderBy('start', 'desc')->get();

        return View::make('pages.pages.edit')
            ->with('lans', $lans)
            ->with('page', $page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $httpRequest
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $httpRequest, Lan $lan, Page $page)
    {
        $this->authorize('update', $page);

        // If the page is accessed via the wrong LAN ID, show 404
        if($page->lan_id != $lan->id) {
            abort(404);
        }

        $input = [
            'lan_id' => $lan->id,
            'title' => $httpRequest->input('title'),
            'content' => $httpRequest->input('content'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StorePageRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $page->update($input);

        return redirect()
            ->route('lans.pages.show', ['lan' => $lan, 'page' => $page])
            ->withSuccess(__('phrase.page-successfully-updated', ['title' => $page->title]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Page $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, Page $page)
    {
        $this->authorize('delete', $page);

        // If the page is accessed via the wrong LAN ID, show 404
        if($page->lan_id != $lan->id) {
            abort(404);
        }

        Page::destroy($page->id);

        return redirect()
            ->route('lans.pages.index', ['lan' => $lan])
            ->withSuccess(__('phrase.page-successfully-deleted', ['title' => $page->title]));
    }
}

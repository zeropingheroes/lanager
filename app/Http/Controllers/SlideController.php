<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use View;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Requests\StoreSlideRequest;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function index(Lan $lan)
    {
        $this->authorize('index', Slide::class);

        $slides = $lan->slides()
            ->orderBy('position')
            ->get();

        return View::make('pages.slides.index')
            ->with('lan', $lan)
            ->with('slides', $slides);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function create(Lan $lan)
    {
        $this->authorize('create', Slide::class);

        return View::make('pages.slides.create')
            ->with('lan', $lan)
            ->with('slide', new Slide());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', Slide::class);

        $input = [
            'lan_id' => $httpRequest->input('lan_id') ?? $lan->id,
            'name' => $httpRequest->input('name'),
            'content' => $httpRequest->input('content'),
            'position' => $httpRequest->input('position'),
            'duration' => $httpRequest->input('duration'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreSlideRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $slide = Slide::create($input);

        return redirect()
            ->route('lans.slides.index', ['lan' => $lan, 'slide' => $slide]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @param Slide $slide
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function show(Lan $lan, Slide $slide)
    {
        $this->authorize('view', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        return View::make('pages.slides.show')
            ->with('lan', $lan)
            ->with('slide', $slide);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @param Slide $slide
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, Slide $slide)
    {
        $this->authorize('update', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        return View::make('pages.slides.edit')
            ->with('lan', $lan)
            ->with('slide', $slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @param Slide $slide
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan, Slide $slide)
    {
        $this->authorize('update', $slide);

        $input = [
            'lan_id' => $lan->id,
            'name' => $httpRequest->input('name'),
            'content' => $httpRequest->input('content'),
            'position' => $httpRequest->input('position'),
            'duration' => $httpRequest->input('duration'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreSlideRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $slide->update($input);

        return redirect()
            ->route('lans.slides.index', ['lan' => $lan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Lan   $lan
     * @param  Slide $slide
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Slide $slide)
    {
        $this->authorize('delete', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        Slide::destroy($slide->id);

        Session::flash(
            'success',
            trans(
                'phrase.item-name-deleted',
                [
                    'item' => trans('title.slide'),
                    'name' => $slide->name,
                ]
            )
        );

        return redirect()->route('lans.slides.index', ['lan' => $lan]);
    }
}

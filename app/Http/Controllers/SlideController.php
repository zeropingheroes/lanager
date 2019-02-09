<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Slide;
use Zeropingheroes\Lanager\Requests\StoreSlideRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Http\Response
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
     * @param Lan $lan
     * @return \Illuminate\Http\Response
     */
    public function create(Lan $lan)
    {
        $this->authorize('create', Slide::class);

        return View::make('pages.slides.create')
            ->with('lan', $lan)
            ->with('slide', new Slide);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $httpRequest
     * @param Lan $lan
     * @return \Illuminate\Http\Response
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', Slide::class);

        $input = [
            'lan_id' => $httpRequest->input('lan_id') ?? $lan->id,
            'name' => $httpRequest->input('name'),
            'content' => $httpRequest->input('content'),
            'position' => $httpRequest->input('position'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreSlideRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $slide = Slide::create($input);

        return redirect()
            ->route('lans.slides.index', ['lan' => $lan, 'slide' => $slide]);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Slide $slide
     * @return \Illuminate\Http\Response
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
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Slide $slide
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request $httpRequest
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Slide $slide
     * @return \Illuminate\Http\Response
     */
    public function update(Request $httpRequest, Lan $lan, Slide $slide)
    {
        $this->authorize('update', $slide);

        $input = [
            'lan_id' => $lan->id,
            'name' => $httpRequest->input('name'),
            'content' => $httpRequest->input('content'),
            'position' => $httpRequest->input('position'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreSlideRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $slide->update($input);

        return redirect()
            ->route('lans.slides.index', ['lan' => $lan, 'slide' => $slide]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param  \Zeropingheroes\Lanager\Slide $slide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, Slide $slide)
    {
        $this->authorize('delete', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        Slide::destroy($slide->id);

        return redirect()
            ->route('lans.slides.index', ['lan' => $lan])
            ->withSuccess(__('phrase.item-name-deleted', [
                'item' => __('title.slide'),
                'name' => $slide->name
            ]));
    }
}

<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Slide as SlideResource;
use Zeropingheroes\Lanager\Slide;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $slides = Slide::where('published', 1);

        if ($request->filled('limit')) {
            $slides->limit($request->limit);
        }

        $slides = $slides->orderBy('position')->get();

        return SlideResource::collection($slides);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Slide $slide
     * @return SlideResource
     */
    public function show(Slide $slide)
    {
        $slide->load('lan');
        return new SlideResource($slide);
    }
}

<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Slide as SlideResource;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Slide;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return AnonymousResourceCollection
     */
    public function index(Lan $lan)
    {
        $slides = $lan->slides()
            ->where('published', 1)
            ->orderBy('position')
            ->get();

        $validSlides = $slides->filter(function ($value) {
            if (($value->start == null) && ($value->end == null)) {
                return true;
            }
            if (($value->start == null || $value->start <= \Carbon\Carbon::now()) && ($value->end == null || $value->end >= \Carbon\Carbon::now())) {
                return true;
            } else {
                return false;
            }
        });

        return SlideResource::collection($validSlides);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param Slide $slide
     * @return SlideResource
     * @throws AuthorizationException
     */
    public function show(Lan $lan, Slide $slide)
    {
        $this->authorize('view', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        return new SlideResource($slide);
    }
}

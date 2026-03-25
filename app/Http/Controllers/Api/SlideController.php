<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\SlideResource;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lan $lan): AnonymousResourceCollection
    {
        $slides = Slide::where('lan_id', $lan->id)
            ->visibleNow()
            ->orderBy('position')
            ->get();

        return SlideResource::collection($slides);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Lan $lan, Slide $slide): SlideResource
    {
        $this->authorize('view', $slide);

        // If the slide is accessed via the wrong LAN ID, show 404
        if ($slide->lan_id != $lan->id) {
            abort(404);
        }

        return new SlideResource($slide);
    }
}

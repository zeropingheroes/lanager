<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Lan as LanResource;
use Zeropingheroes\Lanager\Lan;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return LanResource::collection(Lan::orderBy('start', 'desc')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @param Request $request
     * @return LanResource
     */
    public function show(Lan $lan, Request $request)
    {
        if ($request->has('users')) {
            $lan->load('users');
        }
        if ($request->has('events')) {
            $lan->load('events');
        }
        if ($request->has('slides')) {
            $lan->load('slides');
        }

        return new LanResource($lan);
    }
}

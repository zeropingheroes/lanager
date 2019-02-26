<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

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
     * @return LanResource
     */
    public function show(Lan $lan)
    {
        $lan->load('users');
        return new LanResource($lan);
    }
}

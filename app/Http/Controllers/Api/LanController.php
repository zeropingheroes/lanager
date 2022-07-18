<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Lan as LanResource;
use Zeropingheroes\Lanager\Lan;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $lans = Lan::where('published', true)
            ->orderBy('start', 'desc')
            ->get();

        return LanResource::collection($lans);
    }

    /**
     * Display the specified resource.
     *
     * @param  Lan     $lan
     * @param  Request $request
     * @return LanResource
     */
    public function show(Lan $lan, Request $request)
    {
        if (!$lan->published) {
            throw new NotFoundHttpException();
        }

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

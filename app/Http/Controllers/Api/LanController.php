<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\LanResource;
use Zeropingheroes\Lanager\Models\Lan;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $lans = Lan::where('published', true)
            ->orderBy('start', 'desc')
            ->get();

        return LanResource::collection($lans);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lan $lan, Request $request): LanResource
    {
        if (! $lan->published) {
            abort(404);
        }

        if ($request->has('users')) {
            $lan->load('users');
        }

        if ($request->has('events')) {
            $lan->load([
                'events' => function ($query): void {
                    $query->where('published', true);
                },
            ]);
        }

        if ($request->has('slides')) {
            $lan->load([
                'slides' => function ($query): void {
                    $query->where('published', true);
                },
            ]);
        }

        if ($request->has('venue')) {
            $lan->load('venue');
        }

        return new LanResource($lan);
    }
}

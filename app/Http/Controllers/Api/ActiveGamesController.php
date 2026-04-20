<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\ActiveGameResource;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;

class ActiveGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $activeGames = (new GetActiveGamesService)->get();

        if ($request->filled('limit')) {
            $activeGames = $activeGames->take($request->limit);
        }

        return ActiveGameResource::collection($activeGames);
    }
}

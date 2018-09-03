<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\Requests\StoreAchievementRequest;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $achievements = Achievement::all();

        return View::make('pages.achievements.index')
            ->with('achievements', $achievements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->authorize('create', Achievement::class);

        return View::make('pages.achievements.create')
            ->with('achievement', new Achievement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Achievement::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
        ];

        $request = new StoreAchievementRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $achievement = Achievement::create($input);

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Display the specified resource.
     *
     * @param Achievement $achievement
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Achievement $achievement)
    {
        $this->authorize('view', $achievement);

        return View::make('pages.achievements.show')
            ->with('achievement', $achievement);
    }
}

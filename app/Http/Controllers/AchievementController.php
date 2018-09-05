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

    /**
     * Show the form for editing the specified resource.
     *
     * @param Achievement $achievement
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Achievement $achievement)
    {
        $this->authorize('update', $achievement);

        return View::make('pages.achievements.edit')
            ->with('achievement', $achievement);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param Achievement $achievement
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Request $httpRequest, Achievement $achievement)
    {
        $this->authorize('update', $achievement);

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
        $achievement->update($input);

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Achievement $achievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Achievement $achievement)
    {
        $this->authorize('delete', $achievement);

        Achievement::destroy($achievement->id);

        return redirect()
            ->route('achievements.index')
            ->withSuccess(__('phrase.item-name-deleted', ['item' => __('title.achievement'), 'name' => $achievement->name]));

    }
}

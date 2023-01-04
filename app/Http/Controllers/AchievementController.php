<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Requests\StoreAchievementRequest;

class AchievementController extends Controller
{
    /**
     * Uploaded image storage location.
     */
    public const DIRECTORY = 'public/images/achievements';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
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
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Achievement::class);

        return View::make('pages.achievements.create')
            ->with('achievement', new Achievement());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Achievement::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'image' => $httpRequest->image,
        ];

        $request = new StoreAchievementRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $achievement = Achievement::create($input);

        if ($httpRequest->image) {
            $extension = $httpRequest->image->getClientOriginalExtension();
            $newFileName = $achievement->id . '.' . strtolower($extension);
            $httpRequest->image->storeAs($this::DIRECTORY, $newFileName);
            $achievement->update(['image_filename' => $newFileName]);
        }

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Display the specified resource.
     *
     * @param  Achievement $achievement
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @param  Achievement $achievement
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @param  Request     $httpRequest
     * @param  Achievement $achievement
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Achievement $achievement)
    {
        $this->authorize('update', $achievement);

        $input = [
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'image' => $httpRequest->image,
            'id' => $achievement->id,
        ];

        $request = new StoreAchievementRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        if ($httpRequest->image) {
            $extension = $httpRequest->image->getClientOriginalExtension();
            $newFileName = $achievement->id . '.' . strtolower($extension);
            $httpRequest->image->storeAs($this::DIRECTORY, $newFileName);
            $input['image_filename'] = $newFileName;
        }

        $achievement->update($input);

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Achievement $achievement
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Achievement $achievement)
    {
        $this->authorize('delete', $achievement);

        Achievement::destroy($achievement->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.achievement'), 'name' => $achievement->name])
        );

        return redirect()->route('achievements.index');
    }
}

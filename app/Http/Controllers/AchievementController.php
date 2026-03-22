<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Requests\StoreAchievementRequest;

class AchievementController extends Controller
{
    /**
     * Uploaded image storage location.
     */
    public const string DIRECTORY = 'public/images/achievements';

    /**
     * Display a listing of the resource.
     */
    public function index(): ViewContract
    {
        $achievements = Achievement::all();

        return View::make('pages.achievements.index')
            ->with('achievements', $achievements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     */
    public function create(): ViewContract
    {
        $this->authorize('create', Achievement::class);

        return View::make('pages.achievements.create')
            ->with('achievement', new Achievement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
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
            $newFileName = $achievement->id.'.'.strtolower($extension);
            $httpRequest->image->storeAs(self::DIRECTORY, $newFileName);
            $achievement->update(['image_filename' => $newFileName]);
        }

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Achievement $achievement): ViewContract
    {
        $this->authorize('view', $achievement);

        return View::make('pages.achievements.show')
            ->with('achievement', $achievement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(Achievement $achievement): ViewContract
    {
        $this->authorize('update', $achievement);

        return View::make('pages.achievements.edit')
            ->with('achievement', $achievement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Achievement $achievement): RedirectResponse
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
            $newFileName = $achievement->id.'.'.strtolower($extension);
            $httpRequest->image->storeAs(self::DIRECTORY, $newFileName);
            $input['image_filename'] = $newFileName;
        }

        $achievement->update($input);

        return redirect()
            ->route('achievements.show', $achievement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Achievement $achievement): RedirectResponse
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

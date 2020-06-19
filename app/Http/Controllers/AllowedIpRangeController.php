<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;
use Zeropingheroes\Lanager\AllowedIpRange;
use Zeropingheroes\Lanager\Requests\StoreAllowedIpRangeRequest;

class AllowedIpRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $allowedIpRanges = AllowedIpRange::all();

        return View::make('pages.allowed-ip-ranges.index')
            ->with('allowedIpRanges', $allowedIpRanges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', AllowedIpRange::class);

        return View::make('pages.allowed-ip-ranges.create')
            ->with('allowedIpRange', new AllowedIpRange());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', AllowedIpRange::class);

        $input = [
            'ip_range' => $httpRequest->input('ip_range'),
            'description' => $httpRequest->input('description'),
        ];

        $request = new StoreAllowedIpRangeRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        AllowedIpRange::create($input);

        return redirect()
            ->route('allowed-ip-ranges.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AllowedIpRange $allowedIpRange
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(AllowedIpRange $allowedIpRange)
    {
        $this->authorize('update', $allowedIpRange);

        return View::make('pages.allowed-ip-ranges.edit')
            ->with('allowedIpRange', $allowedIpRange);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param AllowedIpRange $allowedIpRange
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, AllowedIpRange $allowedIpRange)
    {
        $this->authorize('update', $allowedIpRange);

        $input = [
            'ip_range' => $httpRequest->input('ip_range'),
            'description' => $httpRequest->input('description'),
            'id' => $allowedIpRange->id,
        ];

        $request = new StoreAllowedIpRangeRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $allowedIpRange->update($input);

        return redirect()
            ->route('allowed-ip-ranges.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AllowedIpRange $allowedIpRange
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(AllowedIpRange $allowedIpRange)
    {
        $this->authorize('delete', $allowedIpRange);

        AllowedIpRange::destroy($allowedIpRange->id);
        Session::flash(
            'success',
            trans(
                'phrase.item-name-deleted',
                [
                    'item' => trans('title.ip-range'),
                    'name' => $allowedIpRange->ip_range,
                ]
            )
        );

        return redirect()->route('allowed-ip-ranges.index');
    }
}

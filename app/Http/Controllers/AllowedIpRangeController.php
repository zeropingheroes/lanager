<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\AllowedIpRange;
use Zeropingheroes\Lanager\Requests\StoreAllowedIpRangeRequest;

class AllowedIpRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ViewContract
    {
        $allowedIpRanges = AllowedIpRange::all();

        return View::make('pages.allowed-ip-ranges.index')
            ->with('allowedIpRanges', $allowedIpRanges);
    }

    /**
     * Show the form for creating a new resource.
     * @throws AuthorizationException
     */
    public function create(): ViewContract
    {
        $this->authorize('create', AllowedIpRange::class);

        return View::make('pages.allowed-ip-ranges.create')
            ->with('allowedIpRange', new AllowedIpRange());
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
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
     * @throws AuthorizationException
     */
    public function edit(AllowedIpRange $allowedIpRange): ViewContract
    {
        $this->authorize('update', $allowedIpRange);

        return View::make('pages.allowed-ip-ranges.edit')
            ->with('allowedIpRange', $allowedIpRange);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, AllowedIpRange $allowedIpRange): RedirectResponse
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
     * @throws AuthorizationException
     */
    public function destroy(AllowedIpRange $allowedIpRange): RedirectResponse
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

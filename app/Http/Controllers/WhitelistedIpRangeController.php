<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;
use Zeropingheroes\Lanager\Requests\StoreWhitelistedIpRangeRequest;
use Zeropingheroes\Lanager\WhitelistedIpRange;

class WhitelistedIpRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $whitelistedIpRanges = WhitelistedIpRange::all();

        return View::make('pages.whitelisted-ip-ranges.index')
            ->with('whitelistedIpRanges', $whitelistedIpRanges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WhitelistedIpRange::class);

        return View::make('pages.whitelisted-ip-ranges.create')
            ->with('whitelistedIpRange', new WhitelistedIpRange);
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
        $this->authorize('create', WhitelistedIpRange::class);

        $input = [
            'ip_range' => $httpRequest->input('ip_range'),
            'description' => $httpRequest->input('description'),
        ];

        $request = new StoreWhitelistedIpRangeRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        WhitelistedIpRange::create($input);

        return redirect()
            ->route('whitelisted-ip-ranges.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(WhitelistedIpRange $whitelistedIpRange)
    {
        $this->authorize('update', $whitelistedIpRange);

        return View::make('pages.whitelisted-ip-ranges.edit')
            ->with('whitelistedIpRange', $whitelistedIpRange);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, WhitelistedIpRange $whitelistedIpRange)
    {
        $this->authorize('update', $whitelistedIpRange);

        $input = [
            'ip_range' => $httpRequest->input('ip_range'),
            'description' => $httpRequest->input('description'),
            'id' => $whitelistedIpRange->id,
        ];

        $request = new StoreWhitelistedIpRangeRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $whitelistedIpRange->update($input);

        return redirect()
            ->route('whitelisted-ip-ranges.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WhitelistedIpRange $whitelistedIpRange
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(WhitelistedIpRange $whitelistedIpRange)
    {
        $this->authorize('delete', $whitelistedIpRange);

        WhitelistedIpRange::destroy($whitelistedIpRange->id);
        Session::flash(
            'success',
            trans(
                'phrase.item-name-deleted',
                [
                    'item' => trans('title.ip-range'),
                    'name' => $whitelistedIpRange->ip_range,
                ]
            )
        );

        return redirect()->route('whitelisted-ip-ranges.index');
    }
}

<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\WhitelistedIpRange;
use Zeropingheroes\Lanager\Requests\StoreWhitelistedIpRangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class WhitelistedIpRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $httpRequest
     * @return \Illuminate\Http\Response
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
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $whitelistedIpRange = WhitelistedIpRange::create($input);

        return redirect()
            ->route('whitelisted-ip-ranges.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\WhitelistedIpRange $whitelistedIpRange
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $httpRequest
     * @param  \Zeropingheroes\Lanager\WhitelistedIpRange $whitelistedIpRange
     * @return \Illuminate\Http\Response
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
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $whitelistedIpRange->update($input);

        return redirect()
            ->route('whitelisted-ip-ranges.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\WhitelistedIpRange $whitelistedIpRange
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhitelistedIpRange $whitelistedIpRange)
    {
        $this->authorize('delete', $whitelistedIpRange);

        WhitelistedIpRange::destroy($whitelistedIpRange->id);

        return redirect()
            ->route('whitelisted-ip-ranges.index')
            ->withSuccess(__('phrase.item-name-deleted', [
                'item' => __('title.ip-range'),
                'name' => $whitelistedIpRange->ip_range
            ]));
    }
}

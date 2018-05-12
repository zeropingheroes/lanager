<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\NavigationLink;
use Illuminate\Http\Request;

class NavigationLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', NavigationLink::class);

        return View::make('pages.navigation-link.index')
            ->with('navigationLinks', NavigationLink::whereNull('parent_id')->with('children')->orderBy('position')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink  $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function show(NavigationLink $navigationLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink  $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function edit(NavigationLink $navigationLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zeropingheroes\Lanager\NavigationLink  $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NavigationLink $navigationLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\NavigationLink  $navigationLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(NavigationLink $navigationLink)
    {
        //
    }
}

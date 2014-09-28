<?php
/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
*/

View::composer('layouts.default.infopages', function($view)
{
	$infoPagesMenuItems = Zeropingheroes\Lanager\InfoPages\InfoPage::whereNull('parent_id')->get();

	$view->with('infoPages', $infoPagesMenuItems);
});

View::composer('layouts.default.playlists', function($view)
{
	$playlists = Zeropingheroes\Lanager\Playlists\Playlist::all();

	$view->with('playlists', $playlists);
});